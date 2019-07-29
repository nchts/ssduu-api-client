<?php

namespace App;

use GuzzleHttp\Client;

/**
 * Архив
 *
 * @author Заец Алексей Сергеевич <ZaecAS@nchts.tatenergo.ru>
 */
class Archive
{
    /**
     * Минимальная и максимальная дата архива
     * @param Client $client
     * @param string $accessToken
     * @param int $id
     * @return array
     */
    public function range(Client $client, string $accessToken, int $id)
    {
        try {
            $response = $client->post('archive/range', [
                'headers' => ['Authorization' => "Bearer {$accessToken}"],
                'query' => [
                    'id' => $id,
                ],
            ]);
        } catch (\Exception $ex) {
            return [
                $ex->getCode(),
                $ex->getMessage(),
            ];
        }
        
        return [
            $response->getStatusCode(),
            Decode::body($response),
        ];
    }

    /**
     * Загрузка архивов холодной воды
     * @param Client $client
     * @param string $accessToken
     * @param int $id
     * @param string $start
     * @param string $end
     * @return @return ['status', 'response']
     */
    public function loadColdWater(Client $client, string $accessToken, int $id, $start, $end)
    {
         try {
            $response = $client->post('archive/read-cold-water', [
                'headers' => ['Authorization' => "Bearer {$accessToken}"],
                'query' => [
                    'id' => $id,
                    'start' => $start,
                    'end' => $end,
                ],
            ]);
        } catch (\Exception $ex) {
            return [
                $ex->getCode(),
                $ex->getMessage(),
            ];
        }

        return [
            $response->getStatusCode(),
            Decode::body($response),
        ];
    }
}