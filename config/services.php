<?php

return [

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'n8n' => [
        'webhook_url' => env('N8N_WEBHOOK_URL', 'http://n8n:5678/webhook/chat-api'),
        'dss_webhook_url' => env('N8N_DSS_WEBHOOK_URL', 'http://n8n:5678/webhook/dss-api'),
        'dss_callback_url' => env('N8N_DSS_CALLBACK_URL', 'http://laravel.test/api/dss/callback'),
        'dss_async' => filter_var(env('N8N_DSS_ASYNC', true), FILTER_VALIDATE_BOOLEAN),
        'sentiment_webhook_url' => env('N8N_SENTIMENT_WEBHOOK_URL', 'http://n8n:5678/webhook/sentiment-api'),
        'assistant_webhook_url' => env('N8N_ASSISTANT_WEBHOOK_URL', 'http://n8n:5678/webhook/assistant-api'),
        'api_key' => env('N8N_API_KEY'),
        'chat_timeout' => (int) env('N8N_CHAT_TIMEOUT', 45),
        'dss_dispatch_timeout' => (int) env('N8N_DSS_DISPATCH_TIMEOUT', 20),
    ],

    'ollama' => [
        'url' => env('OLLAMA_URL', 'http://127.0.0.1:11434'),
        'base_url' => env('OLLAMA_BASE_URL', 'http://host.docker.internal:11434'),
        'model' => env('OLLAMA_MODEL', 'qwen2.5:3b'),
        'dss_model' => env('OLLAMA_DSS_MODEL', 'qwen2.5:1.5b'),
    ],

];
