<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CraigService
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string              $craigUrl,
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir,
    )
    {
    }

    public function getRecordings(): array
    {
        $response = $this->httpClient->request(
            'GET',
            $this->craigUrl . '/api/recordings'
        );

        return $response->toArray()['recordings'];
    }

    public function getDuration(string $id, string $key): float
    {
        $response = $this->httpClient->request(
            'GET',
            $this->craigUrl . '/api/recording/' . $id . '/duration?key=' . $key
        );

        return $response->toArray()['duration'];
    }

    public function downloadAudio(string $id, string $key): string
    {
        $response = $this->httpClient->request(
            'POST',
            $this->craigUrl . '/api/recording/' . $id . '/cook?key=' . $key,
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'format' => 'mp3',
                    'container' => 'mix',
                    'dynaudnorm' => false,
                    'type' => 'default'
                ],
            ]
        );

        $data = $response->toArray();

        $data = ['ready' => false];

        while (!$data['ready']) {
            sleep(5);
            $response = $this->httpClient->request(
                'GET',
                $this->craigUrl . '/api/recording/' . $id . '/cook?key=' . $key,
            );

            $data = $response->toArray();
        }

        $fileName = $data['download']['file'];
        $downloadUrl = $this->craigUrl . '/dl/' . $fileName;
        $filePath = $this->projectDir . '/assets/audios/' . $fileName;

        $response = $this->httpClient->request('GET', $downloadUrl);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Failed to download file.');
        }

        $source = $response->toStream();
        $destination = fopen($filePath, 'w');

        stream_copy_to_stream($source, $destination);

        fclose($destination);

        return $fileName;
    }
}
