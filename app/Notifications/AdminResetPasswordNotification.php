<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminResetPasswordNotification extends Notification
{
    use Queueable;
    public $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url(url('/') . route('admin.password.reset.link', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false));
        $expire = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');
        return (new MailMessage)
            ->subject(mailTemplate('password_reset')->subject)
            ->markdown('emails.default', [
                'body' => mailTemplate('password_reset')->body,
                'short_codes' => [
                    '{{link}}' => $url,
                    '{{expiry_time}}' => $expire,
                    '{{website_name}}' => settings('general')->site_name,
                ],
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
