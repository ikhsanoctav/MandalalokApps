<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChatbotTest extends TestCase
{
    public function test_chatbot_validation_fails_without_message(): void
    {
        $response = $this->postJson(route('api.chat'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['message']);
    }

    public function test_chatbot_successful_response(): void
    {
        $url = config('services.n8n.webhook_url', 'http://n8n:5678/webhook/chat-api');

        Http::fake([
            $url => Http::response([
                'output' => 'Halo! Ini adalah respons dari AI n8n.'
            ], 200)
        ]);

        $response = $this->postJson(route('api.chat'), [
            'message' => 'Halo n8n',
            'name' => 'Budi',
            'kelurahan' => 'Pasirlayung',
            'phone' => '08123456789'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'reply' => 'Halo! Ini adalah respons dari AI n8n.'
            ]);

        Http::assertSent(function ($request) use ($url) {
            return $request->url() === $url
                && $request['message'] === 'Halo n8n'
                && isset($request['systemContext'])
                && isset($request['sessionId']);
        });
    }

    public function test_chatbot_handles_n8n_failure_gracefully(): void
    {
        $url = config('services.n8n.webhook_url', 'http://n8n:5678/webhook/chat-api');

        Http::fake([
            $url => Http::response('Server Error', 500)
        ]);

        $response = $this->postJson(route('api.chat'), [
            'message' => 'Halo n8n'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_chatbot_context_endpoint(): void
    {
        $response = $this->getJson(route('api.chatbot.context'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'statistics' => [
                    'total_umkm',
                    'growth',
                    'top_kategori',
                    'top_kategori_total',
                ],
                'kategori_distribusi',
                'berita_terbaru',
                'daftar_umkm'
            ]);
    }
}
