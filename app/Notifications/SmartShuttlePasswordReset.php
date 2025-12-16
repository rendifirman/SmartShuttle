<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SmartShuttlePasswordReset extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🔐 Reset Password - SmartShuttle')
            ->greeting('Halo!')
            ->line('Kami menerima permintaan reset password untuk akun SmartShuttle Anda.')
            ->line('**Kode reset password Anda adalah:**')
            ->line('## ' . $this->token . ' ##')
            ->line('Kode ini akan kadaluarsa dalam 60 menit.')
            ->line('Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini.')
            ->action('Kembali ke SmartShuttle', url('/'))
            ->line('Terima kasih telah menggunakan SmartShuttle!')
            ->salutation('Salam hangat,<br>Tim SmartShuttle');
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