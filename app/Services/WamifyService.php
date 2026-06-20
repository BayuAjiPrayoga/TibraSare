<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WamifyService
{
    /**
     * Send a text message via Wamify.
     *
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public static function sendMessage(string $phone, string $message): bool
    {
        $token = env('WAMIFY_TOKEN');
        $sessionId = env('WAMIFY_SESSION_ID');

        if (empty($token) || empty($sessionId)) {
            Log::warning('Wamify token or session ID is not configured.');
            return false;
        }

        // Format phone number (ensure no leading 0 or +)
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $url = "https://wa.rezzaflamingo.com/api/v1/{$sessionId}/messages/send/text";

        try {
            $response = Http::withToken($token)
                ->post($url, [
                    'recipientPN' => [$phone],
                    'text' => $message,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['success']) && $data['success'] === true) {
                    Log::info('Wamify Success: ', $data);
                    return true;
                }
                
                Log::error('Wamify Failed Response: ' . $response->body());
                return false;
            }

            Log::error('Wamify Error: ' . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error('Wamify Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a media message (image/video/document) via Wamify.
     *
     * @param string $phone
     * @param string $mediaUrl
     * @param string $caption
     * @return bool
     */
    public static function sendMediaMessage(string $phone, string $mediaUrl, string $caption = ''): bool
    {
        $token = env('WAMIFY_TOKEN');
        $sessionId = env('WAMIFY_SESSION_ID');

        if (empty($token) || empty($sessionId)) {
            Log::warning('Wamify token or session ID is not configured.');
            return false;
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $url = "https://wa.rezzaflamingo.com/api/v1/{$sessionId}/messages/send/media";

        try {
            $response = Http::withToken($token)
                ->post($url, [
                    'recipientPN' => [$phone],
                    'url' => $mediaUrl,
                    'caption' => $caption,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['success']) && $data['success'] === true) {
                    Log::info('Wamify Media Success: ', $data);
                    return true;
                }
                Log::error('Wamify Media Failed Response: ' . $response->body());
                return false;
            }

            Log::error('Wamify Media Error: ' . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error('Wamify Media Exception: ' . $e->getMessage());
            return false;
        }
    }
}
