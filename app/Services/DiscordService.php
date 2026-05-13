<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiscordService
{
    private ?string $webhookUrl;

    public function __construct()
    {
        $this->webhookUrl = config('services.discord.webhook_url');
    }

    public function sendError(string $endpoint, string $method, string $errorMessage, string $ip): void
    {
        $this->send([
            'embeds' => [[
                'title'       => '🚨 Error 500 en la API',
                'color'       => 15158332, // rojo
                'fields'      => [
                    ['name' => 'Endpoint',  'value' => $endpoint,     'inline' => true],
                    ['name' => 'Método',    'value' => $method,       'inline' => true],
                    ['name' => 'IP',        'value' => $ip,           'inline' => true],
                    ['name' => 'Error',     'value' => $errorMessage, 'inline' => false],
                    ['name' => 'Fecha',     'value' => now()->toDateTimeString(), 'inline' => true],
                ],
            ]],
        ]);
    }

    public function sendRateLimit(string $endpoint, string $ip): void
    {
        $this->send([
            'embeds' => [[
                'title'  => '⚠️ Rate Limit Excedido',
                'color'  => 16776960, // amarillo
                'fields' => [
                    ['name' => 'Endpoint',  'value' => $endpoint,               'inline' => true],
                    ['name' => 'IP',        'value' => $ip,                     'inline' => true],
                    ['name' => 'Timestamp', 'value' => now()->toDateTimeString(), 'inline' => true],
                ],
            ]],
        ]);
    }

    private function send(array $payload): void
    {
        if (!$this->webhookUrl) return;

        try {
            Http::post($this->webhookUrl, $payload);
        } catch (\Throwable $e) {
            Log::error('Discord webhook error: ' . $e->getMessage());
        }
    }
}
