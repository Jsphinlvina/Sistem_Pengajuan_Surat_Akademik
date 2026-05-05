<?php

namespace App\Notifications;

use App\Models\Pengajuan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanStatusNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Pengajuan $pengajuan)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
       return [
            'pengajuan_id' => $this->pengajuan->id,
            'judul' => 'Status Pengajuan',
            'pesan' => match ($this->pengajuan->status) {
                Pengajuan::status_disetujui => 'Pengajuan disetujui',
                Pengajuan::status_ditolak => 'Pengajuan ditolak',
                default => 'Status diperbarui',
            },
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
         return (new MailMessage)
            ->subject('Update Status Pengajuan')
            ->line('Status pengajuan Anda telah diperbarui.')
            ->action(
                'Lihat Detail',
                route('pengajuan.history')
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
