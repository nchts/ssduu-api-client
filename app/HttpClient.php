<?php

namespace App;

use GuzzleHttp\Client;

/**
 * Соединение.
 * Содержит одну функцию для создания клиента на подключение:
 * + create, которая принимает только настройки прокси, если это необходимо.
 *      По умолчанию он отключён, но Вы можете указать свой в формате:
 *          'proxy' => [
                'http'  => 'http://proxy.company.org:3128'
 *          ]
 *      Подробности на странице: http://docs.guzzlephp.org/en/stable/request-options.html?highlight=proxy#proxy
 *
 * @author Заец Алексей Сергеевич <ZaecAS@nchts.tatenergo.ru>
 */
class HttpClient
{
    /**
     * @var float Таймаут соединения
     */
    const TIMEOUT_CONNECTION = 20.0;

    /**
     * Создание класса клиента
     * 
     * @param null|array $proxy
     * @param null|float $timeout
     * @return GuzzleHttp\Client
     */
    public function create($proxy = null, $timeout = null)
    {
        if ($timeout === null) {
            $timeout = static::TIMEOUT_CONNECTION;
        }

         return new Client([
            'base_uri' => 'http://84.18.104.172/api/v1/',
            'timeout'  => $timeout,
            'proxy' => $proxy,
            'allow_redirects' => false,
        ]);
    }
}