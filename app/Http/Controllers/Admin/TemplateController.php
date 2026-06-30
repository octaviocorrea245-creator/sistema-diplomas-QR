<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diploma;
use App\Models\DiplomaTemplate;
use App\Models\Cursos;
use App\Services\DiplomaRenderer;
use App\Services\QrGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    private function departamentoId()
    {
        return auth()->user()->department_id;
    }

    public function index()
    {
        $templates = DiplomaTemplate::whereHas('curso', fn($q) =>
            $q->where('departamento_id', $this->departamentoId())
        )->with('curso', 'elements')->orderBy('id', 'desc')->get();

        return view('admin.templates.index', compact('templates'));
    }

    public function create(Request $request)
    {
        $cursos = Cursos::where('departamento_id', $this->departamentoId())
            ->where('estado', 'activo')
            ->whereDoesntHave('template')
            ->orderBy('nombre')
            ->get();

        $preselectedCurso = null;
        if ($request->query('curso_id')) {
            $preselectedCurso = $cursos->find($request->query('curso_id'));
        }

        return view('admin.templates.create', compact('cursos', 'preselectedCurso'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'curso_id'       => 'required|exists:cursos,id',
            'nombre'         => 'required|string|max:255',
            'canvas_width'   => 'required|integer|min:100|max:4000',
            'canvas_height'  => 'required|integer|min:100|max:4000',
        ]);

        $curso = Cursos::findOrFail($data['curso_id']);
        abort_unless($curso->departamento_id === $this->departamentoId(), 403);

        $template = DiplomaTemplate::create([
            'curso_id'      => $data['curso_id'],
            'nombre'        => $data['nombre'],
            'canvas_width'  => $data['canvas_width'],
            'canvas_height' => $data['canvas_height'],
        ]);

        return redirect()->route('admin.templates.editor', $template)
            ->with('success', 'Plantilla creada. Ahora puedes diseñarla.');
    }

    public function createForCourse(Cursos $curso)
    {
        abort_unless($curso->departamento_id === $this->departamentoId(), 403);

        if ($curso->template) {
            return redirect()->route('admin.templates.editor', $curso->template)
                ->with('info', 'El curso ya tiene una plantilla.');
        }

        $template = DiplomaTemplate::create([
            'curso_id'      => $curso->id,
            'nombre'        => 'Diploma - ' . $curso->nombre,
            'canvas_width'  => 1920,
            'canvas_height' => 1358,
        ]);

        return redirect()->route('admin.templates.editor', $template)
            ->with('success', 'Plantilla creada para «' . $curso->nombre . '». Ahora puedes diseñarla.');
    }

    public function show(DiplomaTemplate $template)
    {
        abort_unless($template->curso->departamento_id === $this->departamentoId(), 403);

        $template->load('elements', 'curso');

        return view('admin.templates.show', compact('template'));
    }

    public function edit(DiplomaTemplate $template)
    {
        abort_unless($template->curso->departamento_id === $this->departamentoId(), 403);

        return view('admin.templates.edit', compact('template'));
    }

    public function update(Request $request, DiplomaTemplate $template)
    {
        abort_unless($template->curso->departamento_id === $this->departamentoId(), 403);

        $data = $request->validate([
            'nombre'        => 'required|string|max:255',
            'canvas_width'  => 'required|integer|min:100|max:4000',
            'canvas_height' => 'required|integer|min:100|max:4000',
        ]);

        $template->update($data);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Plantilla actualizada.');
    }

    public function destroy(DiplomaTemplate $template)
    {
        abort_unless($template->curso->departamento_id === $this->departamentoId(), 403);

        if ($template->background_image) {
            $abs = public_path($template->background_image);
            if (file_exists($abs)) @unlink($abs);
        }

        $template->elements()->delete();
        $template->delete();

        return redirect()->route('admin.templates.index')
            ->with('success', 'Plantilla eliminada.');
    }

    public function editor(DiplomaTemplate $template)
    {
        abort_unless($template->curso->departamento_id === $this->departamentoId(), 403);

        $template->load('elements', 'curso');

        $variables = [
            'full_name'      => 'Nombre del alumno',
            'curso_nombre'   => 'Nombre del curso',
            'curso_horas'    => 'Horas del curso',
            'fecha_inicio'   => 'Fecha de inicio',
            'fecha_fin'      => 'Fecha de fin',
            'fecha_expedicion' => 'Fecha de expedición',
            'folio'          => 'Folio / Identificador',
        ];

        return view('admin.templates.editor', compact('template', 'variables'));
    }

    public function preview(DiplomaTemplate $template)
    {
        abort_unless($template->curso->departamento_id === $this->departamentoId(), 403);
        $template->load('elements', 'curso');

        $diploma = new Diploma();
        $diploma->token_qr = 'preview';
        $diploma->folio = 'DIP-PREVIEW';
        $diploma->fecha_emision = now();
        $diploma->setRelation('alumno', new \App\Models\User([
            'full_name' => 'María García López',
        ]));
        $diploma->setRelation('curso', $template->curso);
        $diploma->setRelation('template', $template);

        $renderer = app(DiplomaRenderer::class);
        $diplomaHtml = $renderer->renderHtml($template, $diploma);

        return view('admin.templates.preview', compact('template', 'diplomaHtml'));
    }

    public function uploadBackground(Request $request, DiplomaTemplate $template)
    {
        abort_unless($template->curso->departamento_id === $this->departamentoId(), 403);

        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        // Remove old file from public dir
        if ($template->background_image) {
            $old = public_path($template->background_image);
            if (file_exists($old)) @unlink($old);
        }

        $file     = $request->file('image');
        $filename = 'bg_' . $template->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $dir      = public_path('uploads/templates');
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $file->move($dir, $filename);

        $relativePath = 'uploads/templates/' . $filename;
        $template->update(['background_image' => $relativePath]);

        return response()->json([
            'url' => asset($relativePath),
        ]);
    }

    public function removeBackground(DiplomaTemplate $template)
    {
        abort_unless($template->curso->departamento_id === $this->departamentoId(), 403);

        if ($template->background_image) {
            $abs = public_path($template->background_image);
            if (file_exists($abs)) @unlink($abs);
            $template->update(['background_image' => null]);
        }

        return response()->json(['success' => true]);
    }

    public function uploadImage(Request $request, DiplomaTemplate $template)
    {
        abort_unless($template->curso->departamento_id === $this->departamentoId(), 403);

        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:10240',
        ]);

        $file     = $request->file('image');
        $filename = 'img_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $dir      = public_path('uploads/templates/images');
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $file->move($dir, $filename);

        $relativePath = 'uploads/templates/images/' . $filename;

        return response()->json([
            'url'  => asset($relativePath),
            'path' => $relativePath,
        ]);
    }

    public function saveElements(Request $request, DiplomaTemplate $template)
    {
        abort_unless($template->curso->departamento_id === $this->departamentoId(), 403);

        $data = $request->validate([
            'elements' => 'required|array',
            'elements.*.id'       => 'nullable|integer|exists:diploma_template_elements,id',
            'elements.*.tipo'     => 'required|string|max:50',
            'elements.*.variable' => 'nullable|string|max:100',
            'elements.*.x'        => 'required|numeric',
            'elements.*.y'        => 'required|numeric',
            'elements.*.width'    => 'required|numeric',
            'elements.*.height'   => 'required|numeric',
            'elements.*.config_json' => 'nullable|json',
            'elements.*.orden'    => 'required|integer',
        ]);

        $existingIds = $template->elements()->pluck('id')->toArray();
        $incomingIds = [];

        foreach ($data['elements'] as $item) {
            $attrs = [
                'template_id' => $template->id,
                'tipo'        => $item['tipo'],
                'variable'    => $item['variable'] ?? null,
                'x'           => $item['x'],
                'y'           => $item['y'],
                'width'       => $item['width'],
                'height'      => $item['height'],
                'config_json' => $item['config_json'] ? json_decode($item['config_json'], true) : null,
                'orden'       => $item['orden'],
            ];

            if (!empty($item['id']) && in_array($item['id'], $existingIds)) {
                $el = $template->elements()->find($item['id']);
                if ($el) {
                    $el->update($attrs);
                    $incomingIds[] = $item['id'];
                }
            } else {
                $el = $template->elements()->create($attrs);
                $incomingIds[] = $el->id;
            }
        }

        $toDelete = array_diff($existingIds, $incomingIds);
        if (!empty($toDelete)) {
            $template->elements()->whereIn('id', $toDelete)->delete();
        }

        return response()->json(['success' => true, 'elements' => $template->elements()->orderBy('orden')->get()]);
    }
}
