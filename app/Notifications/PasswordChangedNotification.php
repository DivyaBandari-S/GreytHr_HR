<?php
 
namespace App\Notifications;
 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
 
 
class PasswordChangedNotification extends Notification
{
    use Queueable;
 
    protected $companyName;
 
    /**
     * Create a new notification instance.
     */
    public function __construct($companyName)
    {
        $this->companyName = $companyName;
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
    public function toMail($notifiable)
    {
        Log::info('Sending password changed email to: ' . $notifiable->email);
        // 1. Get IP Address
        $ipAddress = request()->ip();
 
        // 2. Get location using public API (Photon or ipapi, no composer package)
        $locationData = null;
        try {
            $ipApiBase = env('FINDIP_API_URL', 'https://ipapi.co');
            $locationApi = rtrim($ipApiBase, '/') . '/' . $ipAddress . '/json/';
 
            $response = Http::timeout(5)->get($locationApi);
 
            if ($response->successful()) {
                $locationData = $response->json();
            }
        } catch (\Exception $e) {
            Log::warning("Failed to fetch IP location: " . $e->getMessage());
        }
 
        // 3. Get browser and OS using PHP's User-Agent string
        $userAgent = request()->header('User-Agent');
 
        $device = 'Unknown Device';
        $os = 'Unknown OS';
        $browser = 'Unknown Browser';
 
        // Basic browser detection
        if (strpos($userAgent, 'Chrome') !== false) {
            $browser = 'Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            $browser = 'Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            $browser = 'Safari';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            $browser = 'Edge';
        } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
            $browser = 'Internet Explorer';
        }
 
        // Basic OS detection
        if (preg_match('/Windows NT 10.0/', $userAgent)) {
            $os = 'Windows 10';
        } elseif (preg_match('/Windows NT 6.3/', $userAgent)) {
            $os = 'Windows 8.1';
        } elseif (preg_match('/Macintosh/', $userAgent)) {
            $os = 'macOS';
        } elseif (preg_match('/iPhone/', $userAgent)) {
            $os = 'iOS';
        } elseif (preg_match('/Android/', $userAgent)) {
            $os = 'Android';
        } elseif (preg_match('/Linux/', $userAgent)) {
            $os = 'Linux';
        }
 
        // Device type detection
        if (preg_match('/Mobile|Android|iPhone|iPad/', $userAgent)) {
            $device = 'Mobile';
        } else {
            $device = 'Desktop';
        }
 
        return (new MailMessage)
            ->subject('Your Password Has Been Changed')
            ->view('mails.password_changed', [
                'user' => $notifiable,
                'companyName' => $this->companyName,
                'ipAddress' => $ipAddress,
                'location' => $locationData,
                'browser' => $browser,
                'device' => $device,
                'os' => $os,
                'osVersion' => null, // You can extract version if needed
                'logoUrl' => asset('images/hr_new_blue.png'),
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
 
            // Optionally include other info if needed
        ];
    }
}
 