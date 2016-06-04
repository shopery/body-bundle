<?php

/**
 * This file is part of shopery/body-bundle
 *
 * Copyright (c) 2016 Shopery.com
 */

namespace Shopery\Bundle\BodyBundle\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

use Shopery\Bundle\BodyBundle\Decoder\Decoder;
use Shopery\Bundle\BodyBundle\Request\Body;

class BodyParamConverter implements ParamConverterInterface
{
    /** @var Decoder[] */
    private $decoders;

    public function __construct($decoders)
    {
        $this->decoders = $decoders;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $contentType = $request->getContentType();
        if (empty($contentType) || !isset($this->decoders[$contentType])) {
            return;
        }

        $content = $request->getContent();
        $decoded = $this->decoders[$contentType]->decode($content);
        $body = new Body($decoded);

        $request->attributes->set($configuration->getName(), $body);
    }

    public function supports(ParamConverter $configuration)
    {
        return Body::class === $configuration->getClass();
    }
}
