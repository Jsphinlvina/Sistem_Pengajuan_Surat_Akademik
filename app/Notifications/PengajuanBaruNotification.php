<?php

namespace App\Notifications;

use App\Models\Pengajuan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanBaruNotification extends Notification
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
            'judul' => 'Pengajuan Baru',
            'pesan' => 'Pengajuan baru dari '
                . $this->pengajuan->mahasiswa->name,
            'status' => $this->pengajuan->status,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengajuan Surat Baru')
            ->line('Ada pengajuan baru yang perlu diproses.')
            ->action(
                'Lihat Pengajuan',
                route('pengajuan.proses.detail', $this->pengajuan->id)
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
