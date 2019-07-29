<?php

namespace App;

use GuzzleHttp\Client;

/**
 * Соединение.
 * Содержит одну функцию для получения токена:
 * + get, которая принимает имя пользователя и пароль.
 *      Функция возвращает массив с формате ['status', 'response']. Http-статус отличный от 200 является
 *      ошибкой и дальнейшее получение данных невозможно.
 *      Чтобы получить возврат функции в php 7.0 достаточно воспользоваться: [$status, $accessToken]
 *
 * @author Заец Алексей Сергеевич <ZaecAS@nchts.tatenergo.ru>
 */
class AccessToken
{
    /**
     * Получение токена.
     *
     * @param Client $client
     * @param string $username
     * @param string $password
     * @return ['status', 'response']
     */
    public function get(Client $client, string $username, string $password) : array
    {
        try {
            $response = $client->post('user/login', [
                'form_params' => [
                    'username' => $username,
                    'password' => $password,
                ],
            ]);           
            $body = Decode::body($response);
            return [
                $response->getStatusCode(),
                $body->access_token
            ];
        } catch (\Exception $ex) {
            return [
                $ex->getCode(),
                $ex->getMessage(),
            ];
        }
    }
}