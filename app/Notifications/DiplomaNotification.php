<?php

namespace App\Notifications;

use App\Models\Departamento;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DiplomaNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $type,
        public string $message,
        public ?string $url = null,
        public ?int $departamentoId = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $deptName = null;
        if ($this->departamentoId) {
            $dept = Departamento::find($this->departamentoId);
            $deptName = $dept?->name;
        }

        return [
            'type'             => $this->type,
            'message'          => $this->message,
            'url'              => $this->url,
            'departamento_id'  => $this->departamentoId,
            'departamento_nombre' => $deptName,
        ];
    }
}
