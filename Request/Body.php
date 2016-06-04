<?php

/**
 * This file is part of shopery/body-bundle
 *
 * Copyright (c) 2016 Shopery.com
 */

namespace Shopery\Bundle\BodyBundle\Request;

use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Body implements \ArrayAccess, \IteratorAggregate
{
    private $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->content);
    }

    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new BadRequestHttpException(sprintf(
                'Looking for "%s" attribute in body, not found',
                $offset
            ));
        }

        return $this->content[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new BadMethodCallException(sprintf(
            'Unexpected call to "%s", body is read-only.',
            __METHOD__
        ));
    }

    public function offsetUnset($offset)
    {
        throw new BadMethodCallException(sprintf(
            'Unexpected call to "%s", body is read-only.',
            __METHOD__
        ));
    }

    public function getIterator()
    {
        return $this->content;
    }
}
