<?php

/**
 * This file is part of shopery/body-bundle
 *
 * Copyright (c) 2016 Shopery.com
 */

namespace Shopery\Bundle\BodyBundle\Request\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

use Shopery\Bundle\BodyBundle\Decoder\Decoder;
use Shopery\Bundle\BodyBundle\Request\Body;

class BodyArgumentResolver implements ArgumentValueResolverInterface
{
    /** @var Decoder[] */
    private $decoders;

    public function __construct($decoders)
    {
        $this->decoders = $decoders;
    }

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        if ($argument->getType() !== Body::class) {
            return false;
        }

        $contentType = $request->getContentType();
        if (empty($contentType) || !isset($this->decoders[$contentType])) {
            return false;
        }

        return true;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $content = $request->getContent();
        $contentType = $request->getContentType();
        $decoded = $this->decoders[$contentType]->decode($content);

        yield new Body($decoded);
    }
}
