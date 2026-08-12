<?php

namespace App\Helpers;

use App\Models\User;
use App\Notifications\DiplomaNotification;
use Illuminate\Support\Facades\Notification;

class NotifySupervisors
{
    public static function send(int $departamentoId, string $type, string $message, ?string $url = null): void
    {
        $supervisors = User::role('supervisor')->get();

        Notification::send($supervisors, new DiplomaNotification($type, $message, $url, $departamentoId));
    }
}
