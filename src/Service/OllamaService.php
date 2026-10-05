<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OllamaService
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string              $ollamaUrl,
        private string              $modelName,
    )
    {
    }

    public function queryLLM(string $message): array
    {
        $response = $this->httpClient->request(
            'POST',
            $this->ollamaUrl . '/api/chat',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $this->modelName,
                    'messages' => [
                        [
                            "role" => "user",
                            "content" => $message
                        ],
                    ],
                    "stream" => false
                ],
            ]
        );

        return $response->toArray();
    }
}
