<?php

/**
 * This file is part of shopery/body-bundle
 *
 * Copyright (c) 2016 Shopery.com
 */

namespace Shopery\Bundle\BodyBundle\Request;

use ArrayIterator;
use DomainException;
use Iterator;
use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Traversable;
use UnexpectedValueException;

class Body implements \ArrayAccess, \IteratorAggregate
{
    private $content;

    public function __construct($content)
    {
        if (!$this->isValidContent($content)) {
            throw new DomainException(
                sprintf(
                    'Invalid body content: %s',
                    serialize($content)
                )
            );
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
            throw new BadRequestHttpException(
                sprintf(
                    'Looking for "%s" attribute in body, not found',
                    $offset
                )
            );
        }

        return $this->content[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new BadMethodCallException(
            sprintf(
                'Unexpected call to "%s", body is read-only.',
                __METHOD__
            )
        );
    }

    public function offsetUnset($offset)
    {
        throw new BadMethodCallException(
            sprintf(
                'Unexpected call to "%s", body is read-only.',
                __METHOD__
            )
        );
    }

    public function getIterator()
    {
        if (is_array($this->content)) {
            return new ArrayIterator($this->content);
        }

        return $this->content;
    }

    /***********************************************************
     *                                                         *
     *                     HELPER METHODS                      *
     *                                                         *
     ***********************************************************/

    public function string(string $param): string
    {
        $paramValue = $this->nullableString($param);

        if (null === $paramValue) {
            throw new UnexpectedValueException("Requested string value not found in request param <$param>");
        }

        return $paramValue;
    }

    public function nullableString(string $param, ?string $defaultValue = null): ?string
    {
        $paramValue = $this->get($param);

        if (null !== $paramValue && !is_string($paramValue)) {
            throw new UnexpectedValueException(
                sprintf('Expected string in request param <%s>, but found %s', $param, gettype($paramValue))
            );
        }

        return $paramValue ?? $defaultValue;
    }

    public function int(int $param): int
    {
        $paramValue = $this->nullableInt($param);

        if (null === $paramValue) {
            throw new UnexpectedValueException("Requested int value not found in request param <$param>");
        }

        return $paramValue;
    }

    public function nullableInt(int $param, ?int $defaultValue = null): ?int
    {
        $paramValue = $this->get($param);

        if (null !== $paramValue && !is_int($paramValue)) {
            throw new UnexpectedValueException(
                sprintf('Expected int in request param <%s>, but found %s', $param, gettype($paramValue))
            );
        }

        return $paramValue ?? $defaultValue;
    }

    public function float(float $param): float
    {
        $paramValue = $this->nullableFloat($param);

        if (null === $paramValue) {
            throw new UnexpectedValueException("Requested float value not found in request param <$param>");
        }

        return $paramValue;
    }

    public function nullableFloat(float $param, ?float $defaultValue = null): ?float
    {
        $paramValue = $this->get($param);

        if (null !== $paramValue && !is_float($paramValue)) {
            throw new UnexpectedValueException(
                sprintf('Expected float in request param <%s>, but found %s', $param, gettype($paramValue))
            );
        }

        return $paramValue ?? $defaultValue;
    }

    public function bool(bool $param): bool
    {
        $paramValue = $this->nullableBool($param);

        if (null === $paramValue) {
            throw new UnexpectedValueException("Requested bool value not found in request param <$param>");
        }

        return $paramValue;
    }

    public function nullableBool(bool $param, ?bool $defaultValue = null): ?bool
    {
        $paramValue = $this->get($param);

        if (null !== $paramValue && !is_bool($paramValue)) {
            throw new UnexpectedValueException(
                sprintf('Expected bool in request param <%s>, but found %s', $param, gettype($paramValue))
            );
        }

        return $paramValue ?? $defaultValue;
    }

    public function array(array $param): array
    {
        $paramValue = $this->nullableArray($param);

        if (null === $paramValue) {
            throw new UnexpectedValueException("Requested array value not found in request param <$param>");
        }

        return $paramValue;
    }

    public function nullableArray(array $param, ?array $defaultValue = null): ?array
    {
        $paramValue = $this->get($param);

        if (null !== $paramValue && !is_array($paramValue)) {
            throw new UnexpectedValueException(
                sprintf('Expected array in request param <%s>, but found %s', $param, gettype($paramValue))
            );
        }

        return $paramValue ?? $defaultValue;
    }

    public function has(string $param): bool
    {
        return $this->offsetExists($param);
    }

    private function isValidContent($content)
    {
        return
            is_array($content)
            || $content instanceof Traversable
            || $content instanceof Iterator;
    }
}
