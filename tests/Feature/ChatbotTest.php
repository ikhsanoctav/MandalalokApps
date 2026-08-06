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
        config(['services.n8n.webhook_url' => 'https://n8n.test/webhook/chat-api']);
        Http::fake(['https://n8n.test/webhook/chat-api' => Http::response([
            'output' => 'Halo! Ini adalah respons dari n8n.',
        ])]);

        $response = $this->postJson(route('api.chat'), [
            'message' => 'Halo n8n',
            'name' => 'Budi',
            'kelurahan' => 'Pasirlayung',
            'phone' => '08123456789'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'reply' => 'Halo! Ini adalah respons dari n8n.'
            ]);

        Http::assertSent(fn ($request) => $request->url() === 'https://n8n.test/webhook/chat-api'
            && $request['message'] === 'Halo n8n'
            && is_string($request['systemContext']));
    }

    public function test_chatbot_handles_n8n_failure_gracefully(): void
    {
        config(['services.n8n.webhook_url' => 'https://n8n.test/webhook/chat-api']);
        Http::fake(['https://n8n.test/webhook/chat-api' => Http::response([], 502)]);

        $response = $this->postJson(route('api.chat'), [
            'message' => 'Halo Ollama'
        ]);

        $response->assertStatus(503)
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
