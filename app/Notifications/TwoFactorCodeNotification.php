<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorCodeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Hekkensluiter - Verificatiecode')
            ->greeting('Hallo ' . $notifiable->name . ',')
            ->line('Je hebt zojuist geprobeerd in te loggen op Hekkensluiter.')
            ->line('Je verificatiecode is: **' . $this->code . '**')
            ->line('Deze code is 10 minuten geldig.')
            ->line('Als je niet hebt geprobeerd in te loggen, kun je deze email negeren.')
            ->action('Inloggen voltooien', url('/two-factor-challenge'))
            ->line('Bedankt voor het gebruik van Hekkensluiter!')
            ->salutation('Met vriendelijke groet,')
            ->salutation('Het Hekkensluiter team');
    }

    public function toArray($notifiable)
    {
        return [
            'code' => $this->code,
        ];
    }
}