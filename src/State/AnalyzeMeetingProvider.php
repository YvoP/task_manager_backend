<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Service\OllamaService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class AnalyzeMeetingProvider implements ProviderInterface
{
    public function __construct(
        private OllamaService $ollamaService,
        #[Autowire(service: 'api_platform.doctrine.orm.state.item_provider')]
        private ProviderInterface $itemProvider,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $meeting = $this->itemProvider->provide($operation, $uriVariables, $context);
        $message =  json_encode($meeting->getTranscript());

        $result = $this->ollamaService->queryLLM($message);

        return [
            'analysis' => $result['message']['content'] ?? null,
        ];
    }
}
