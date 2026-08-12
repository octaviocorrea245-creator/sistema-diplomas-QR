<x-app-layout>
    <x-slot name="header">
        <h2>{{ $alumno->full_name ?: $alumno->username }}</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <div style="font-size:0.8rem; color:#94a3b8; margin-bottom:1.5rem;">
        <a href="{{ route('supervisor.alumnos.index') }}" style="color:#94a3b8; text-decoration:none;">Alumnos</a>
        <span style="margin:0 6px;">/</span>
        <span style="color:#475569;">{{ $alumno->full_name ?: $alumno->username }}</span>
    </div>

    {{-- Perfil --}}
    <div style="background:#fff; border-radius:12px; border:1px solid #e2e8f0; padding:1.5rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:1.25rem; flex-wrap:wrap;">
        @if($alumno->avatar_url)
            <img src="{{ $alumno->avatar_url }}" alt="Avatar"
                 style="width:52px;height:52px;border-radius:50%;object-fit:cover;flex-shrink:0;">
        @else
            <div style="width:52px; height:52px; border-radius:50%; background:var(--brand-bg); display:flex; align-items:center; justify-content:center; font-size:1.25rem; font-weight:700; color:var(--brand); flex-shrink:0;">
                {{ strtoupper(substr($alumno->full_name ?: $alumno->username, 0, 1)) }}
            </div>
        @endif
        <div style="flex:1;min-width:160px;">
            <p style="font-size:1.05rem; font-weight:700; color:#1e293b; margin:0;">{{ $alumno->full_name ?: $alumno->username }}</p>
            <p style="font-size:0.82rem; color:#64748b; margin:2px 0 0;">
                {{ $alumno->department->name ?? '—' }}
                @if($alumno->username)
                    · <span style="font-family:monospace;">{{ $alumno->username }}</span>
                @endif
            </p>
        </div>
        <button type="button" onclick="openAvatarModal()"
                style="padding:0.35rem 0.75rem;border:none;border-radius:8px;background:#1A56B0;color:#fff;font-size:0.75rem;font-weight:600;cursor:pointer;white-space:nowrap;">
            Subir foto
        </button>
        @error('avatar')
            <p style="width:100%;font-size:0.78rem;color:#DC2626;margin:4px 0 0;">{{ $message }}</p>
        @enderror
    </div>

    {{-- Cursos agrupados por departamento --}}
    <p style="font-size:0.875rem; font-weight:600; color:#475569; margin:0 0 1rem;">
        Cursos inscritos
        <span style="font-weight:400; color:#94a3b8;">({{ $alumno->cursos->count() }})</span>
    </p>

    @if($alumno->cursos->isEmpty())
        <div style="text-align:center; padding:3rem 2rem; background:#fff; border-radius:12px; border:1px solid #e2e8f0; color:#94a3b8;">
            <p style="margin:0;">Este alumno no tiene cursos inscritos.</p>
        </div>
    @else
        @foreach($alumno->cursos->groupBy(fn($c) => $c->departamento->name ?? 'Sin departamento') as $depto => $cursos)
            <div style="margin-bottom:1.5rem;">
                <p style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.05em; color:#94a3b8; font-weight:600; margin:0 0 0.6rem;">
                    {{ $depto }}
                </p>
                <div style="display:flex; flex-direction:column; gap:0.5rem;">
                    @foreach($cursos as $curso)
                        @php
                            $estadoMap = [
                                'inscrito'   => ['bg:#DBEAFE','color:#1E40AF'],
                                'en_curso'   => ['bg:#FEF9C3','color:#854D0E'],
                                'completado' => ['bg:#DCFCE7','color:#166534'],
                                'baja'       => ['bg:#FEE2E2','color:#991B1B'],
                            ];
                            $es = $estadoMap[$curso->pivot->estado] ?? ['bg:#F1F5F9','color:#475569'];
                        @endphp
                        <div style="background:#fff; border-radius:10px; border:1px solid #e2e8f0; padding:1rem 1.25rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                            <div>
                                <p style="font-weight:600; color:#1e293b; margin:0 0 2px;">{{ $curso->nombre }}</p>
                                <p style="font-size:0.78rem; color:#94a3b8; margin:0;">
                                    {{ $curso->horas }} horas
                                    · {{ $curso->fecha_inicio?->format('d/m/Y') }} – {{ $curso->fecha_fin?->format('d/m/Y') }}
                                </p>
                                @if($curso->pivot->fecha_completado)
                                    <p style="font-size:0.78rem; color:#16a34a; margin:2px 0 0;">
                                        Completado el {{ \Carbon\Carbon::parse($curso->pivot->fecha_completado)->format('d/m/Y') }}
                                    </p>
                                @endif
                            </div>
                            <div style="display:flex; align-items:center; gap:0.75rem;">
                                <span style="display:inline-block; padding:0.2rem 0.65rem; border-radius:20px; font-size:0.72rem; font-weight:600; background:{{ str_replace('bg:', '', $es[0]) }}; color:{{ str_replace('color:', '', $es[1]) }};">
                                    {{ ucfirst($curso->pivot->estado) }}
                                </span>
                                <a href="{{ route('supervisor.cursos.alumnos.show', [$curso, $alumno]) }}"
                                   style="font-size:0.8rem; color:var(--brand); text-decoration:none; font-weight:500; white-space:nowrap;">
                                    Ver →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif

    {{-- Avatar Modal --}}
    <div id="avatarModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:16px;width:90%;max-width:400px;box-shadow:0 20px 60px rgba(0,0,0,0.2);overflow:hidden;">
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;">
                <h3 style="font-size:1rem;font-weight:700;color:#0D1B35;margin:0;">Foto de perfil</h3>
                <button type="button" onclick="closeAvatarModal()" style="border:none;background:none;cursor:pointer;color:#94a3b8;font-size:1.25rem;line-height:1;">&times;</button>
            </div>
            <form method="post" action="{{ route('supervisor.alumnos.avatar', $alumno) }}" enctype="multipart/form-data" style="padding:1.5rem;">
                @csrf
                <div style="text-align:center;margin-bottom:1.25rem;">
                    @if($alumno->avatar_url)
                        <img src="{{ $alumno->avatar_url }}" alt="Avatar" style="width:80px;height:80px;border-radius:50%;object-fit:cover;">
                    @else
                        <div style="width:80px;height:80px;border-radius:50%;background:var(--brand-bg);display:flex;align-items:center;justify-content:center;font-size:1.75rem;font-weight:700;color:var(--brand);margin:0 auto;">
                            {{ strtoupper(substr($alumno->full_name ?: $alumno->username, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <label style="display:block;font-size:0.82rem;font-weight:600;color:#475569;margin-bottom:0.5rem;">Seleccionar imagen</label>
                <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" required
                       style="display:block;width:100%;padding:0.5rem 0;font-size:0.85rem;color:#475569;">
                <p style="font-size:0.72rem;color:#94a3b8;margin:0.5rem 0 1.25rem;">JPG, PNG o WebP · Máx 2 MB</p>
                <button type="submit"
                        style="width:100%;padding:0.6rem 1rem;border:none;border-radius:10px;background:#1A56B0;color:#fff;font-size:0.85rem;font-weight:600;cursor:pointer;">
                    Subir foto
                </button>
            </form>
        </div>
    </div>

    <script>
        function openAvatarModal() {
            document.getElementById('avatarModal').style.display = 'flex';
        }
        function closeAvatarModal() {
            document.getElementById('avatarModal').style.display = 'none';
        }
        document.getElementById('avatarModal').addEventListener('click', function(e) {
            if (e.target === this) closeAvatarModal();
        });
    </script>

</x-app-layout>