<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Auth\Notifications\ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Pemberitahuan Reset Password')
                ->greeting('Halo!')
                ->line('Anda menerima email ini karena kami menerima permintaan reset password (buat password baru) untuk akun Anda.')
                ->action('Reset Password', $url)
                ->line('Tautan reset password ini akan kedaluwarsa dalam 60 menit.')
                ->line('Jika Anda tidak meminta reset password, abaikan email ini.')
                ->salutation('Salam hormat, Tim E-Learning');
        });
    }
}
