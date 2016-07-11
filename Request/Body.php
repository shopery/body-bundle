<?php

/**
 * This file is part of shopery/body-bundle
 *
 * Copyright (c) 2016 Shopery.com
 */

namespace Shopery\Bundle\BodyBundle\Request;

use Traversable;
use Iterator;
use DomainException;
use ArrayIterator;

use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Body implements \ArrayAccess, \IteratorAggregate
{
    private $content;

    public function __construct($content)
    {
        if (!$this->isValidContent($content)) {
            throw new DomainException(sprintf(
                'Invalid body content: %s',
                serialize($content)
            ));
        }

        $this->content = $content;
    }

    public function get($offset, $default = null)
    {
        if ($this->offsetExists($offset)) {
            return $this->offsetGet($offset);
        }

        return $default;
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
        if (is_array($this->content)) {
            return new ArrayIterator($this->content);
        }

        return $this->content;
    }

    private function isValidContent($content)
    {
        return
            is_array($content)
            || $content instanceof Traversable
            || $content instanceof Iterator;
    }
}
