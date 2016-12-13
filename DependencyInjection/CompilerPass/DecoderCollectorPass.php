<?php

/**
 * This file is part of shopery/body-bundle
 *
 * Copyright (c) 2016 Shopery.com
 */

namespace Shopery\Bundle\BodyBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DecoderCollectorPass implements CompilerPassInterface
{
    private $serviceId;
    private $tagName;
    private $keyAttribute;

    public function __construct($serviceId, $tagName, $keyAttribute)
    {
        $this->serviceId = $serviceId;
        $this->tagName = $tagName;
        $this->keyAttribute = $keyAttribute;
    }

    public function process(ContainerBuilder $container)
    {
        if (!$container->has($this->serviceId)) {
            return;
        }

        $decoders = $this->collectDecoders($container);
        $this->setDecoders($container, $decoders);
    }

    private function collectDecoders(ContainerBuilder $container): array
    {
        $decoders = [];
        foreach ($container->findTaggedServiceIds($this->tagName) as $id => $tags) {
            foreach ($tags as $tag) {
                if (!isset($tag[$this->keyAttribute])) {
                    throw $this->tagAttributeNotFound($id);
                }

                $decoders[$tag[$this->keyAttribute]] = new Reference($id);
            }
        }

        return $decoders;
    }

    private function setDecoders(ContainerBuilder $container, $decoders)
    {
        $container
            ->findDefinition($this->serviceId)
            ->addArgument($decoders);
    }

    private function tagAttributeNotFound($id)
    {
        return new \InvalidArgumentException(sprintf(
            'Service "%s" is tagged as "%s" but lacks required "%s" attribute.',
            $id,
            $this->tagName,
            $this->keyAttribute
        ));
    }
}
