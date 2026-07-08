<?php

namespace Tests\Feature;

use App\Services\OllamaService;
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
        $this->mock(OllamaService::class, function ($mock) {
            $mock->shouldReceive('generateChatResponse')
                ->once()
                ->withArgs(fn ($systemContext, $message, $history) => is_string($systemContext)
                    && $message === 'Halo Ollama'
                    && $history === [])
                ->andReturn('Halo! Ini adalah respons dari AI Ollama.');
        });

        $response = $this->postJson(route('api.chat'), [
            'message' => 'Halo Ollama',
            'name' => 'Budi',
            'kelurahan' => 'Pasirlayung',
            'phone' => '08123456789'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'reply' => 'Halo! Ini adalah respons dari AI Ollama.'
            ]);
    }

    public function test_chatbot_handles_ollama_failure_gracefully(): void
    {
        $this->mock(OllamaService::class, function ($mock) {
            $mock->shouldReceive('generateChatResponse')
                ->once()
                ->andReturn(null);
        });

        $response = $this->postJson(route('api.chat'), [
            'message' => 'Halo Ollama'
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
