<?php

namespace Shopery\Bundle\BodyBundle\Decoder;

class JsonDecoder implements Decoder
{
    const DECODE_DEPTH = 24;

    public function decode($content)
    {
        return json_decode($content, true, self::DECODE_DEPTH);
    }
}
