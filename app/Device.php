<?php

namespace App;

use GuzzleHttp\Client;

/**
 * Узел учёта
 *
 * @author Заец Алексей Сергеевич <ZaecAS@nchts.tatenergo.ru>
 */
class Device
{
    /**
     * Получение списка.
     * Поля:
     *  + id
     *  + address - Адрес
     *  + address_full - Фактический адрес по улице
     *  + identifier - Идентификатор
     *  + serial - Серийный номер прибора учёта
     *  + device_type - Тип прибора учёта (например: ТСРВ-023, ТСРВ-024 и т.д.)
     *
     * @param Client $client
     * @param string $accessToken
     * @return ['status', 'response']
     */
    public function getList(Client $client, string $accessToken) : array
    {
        try {
            $response = $client->post('device/list', [
                'headers' => ['Authorization' => "Bearer {$accessToken}"],
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