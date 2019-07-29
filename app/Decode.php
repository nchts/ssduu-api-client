<?php

namespace App;

/**
 * Decode helper
 *
 * @author Заец Алексей Сергеевич <ZaecAS@nchts.tatenergo.ru>
 */
class Decode
{
    static public function body(&$response)
    {
        return json_decode($response->getBody()->read($response->getBody()->getSize()));
    }
}