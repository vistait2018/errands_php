<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;


class LoginNotification extends Notification
{
    use Queueable;
    private $user;


    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
       $this->user  = $user;
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
       $ip = request()->ip();

    // Call ipinfo.io (free tier) for geo data


        $response = Http::withOptions([
            'verify' => false,
        ])->get("https://ipinfo.io/json?token=" . env('IPINFO_TOKEN'));


    $city    = data_get($response->json(), 'city', 'Unknown City');
    $region  = data_get($response->json(), 'region', 'Unknown Region');
    $country = data_get($response->json(), 'country', 'Unknown Country');
    $coordinates = data_get($response->json(),'Coordinates','Unknown coordinates');
    $loginLocation = "{$city}, {$region}, {$country}, {$coordinates}";
        $logoUrl       = config('app.url') . '/images/logo.png';
        return (new MailMessage)
        ->subject('Login Notification')
        ->markdown('emails.notifications.login', [
            'user'           => $this->user,
            'logoUrl'        => $logoUrl,
            'loginLocation'  => $loginLocation,
            'loginUrl'       => env('APP_LOGIN_URL')
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {

            return [
                'user' => $this->user,
            ];

    }
}
