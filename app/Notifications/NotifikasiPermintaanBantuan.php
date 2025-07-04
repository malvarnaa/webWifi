<?php

namespace App\Notifications;

use App\Models\PermintaanBantuan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotifikasiPermintaanBantuan extends Notification
{
    use Queueable;

    public $permintaan;

    public function __construct(PermintaanBantuan $permintaan)
    {
        $this->permintaan = $permintaan;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // atau cukup ['database'] kalau tak perlu email
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Permintaan Bantuan Baru dari ' . $this->permintaan->user->name)
            ->line('Jenis: ' . ucfirst($this->permintaan->jenis))
            ->line('Deskripsi: ' . $this->permintaan->deskripsi)
            ->action('Lihat Detail', url('/admin/permintaan/' . $this->permintaan->id));
    }

    public function toArray($notifiable)
    {
        return [
            'jenis' => $this->permintaan->jenis,
            'nama' => $this->permintaan->user->name,
            'deskripsi' => $this->permintaan->deskripsi,
            'id' => $this->permintaan->id,
        ];
    }
}
