<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ManticoreSearchClient
{
    private Client $client;

    public function __construct(string $apiUrl, int $timeout = 10)
    {
        $this->client = new Client([
            'base_uri' => $apiUrl,
            'timeout'  => $timeout,
        ]);
    }

    public function search(string $index, string $query, array $fieldList, int $size): array
    {
        try {

            // Replace `+` or `%20` with `&` to match both terms in Manticore
            $query = str_replace(['+', '%20'], '|', $query);

            $response = $this->client->post('/search', [
                'json' => [
                    'index' => $index,
                    'query' => [
                        'match' => [
                            '*' => $query
                        ]
                    ],
                    'fields' => $fieldList,
                    'size' => $size,
                    'from' => 0
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

    public function searchById(string $index, int $id): array
    {
        try {
            $response = $this->client->post('/search', [
                'json' => [
                    'index' => $index,
                    'query' => [
                        'bool' => [
                            'must' => [
                                ['equals' => ['id' => $id]]
                            ]
                        ]
                    ],
                    'size' => 1
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $ex) {
            return ['error' => $ex->getMessage()];
        }
    }
}
