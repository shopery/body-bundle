<?php

namespace Shopery\Bundle\BodyBundle\Decoder;

class JsonDecoder implements Decoder
{
    public function decode($content)
    {
        return json_decode($content, true, 24);
    }
}
