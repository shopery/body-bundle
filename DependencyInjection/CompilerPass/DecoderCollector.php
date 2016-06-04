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

class DecoderCollector implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $serviceId = 'shopery.body.param_converter';
        if (!$container->has($serviceId)) {
            return;
        }

        $decoders = [];
        $tagName = 'shopery.body_decoder';
        $key = 'type';
        foreach ($container->findTaggedServiceIds($tagName) as $id => $tags) {
            foreach ($tags as $tag) {
                if (!isset($tag[$key])) {
                    throw self::tagAttributeNotFound($id, $tagName, $key);
                }

                $decoders[$tag[$key]] = new Reference($id);
            }
        }

        $container
            ->findDefinition($serviceId)
            ->addArgument($decoders);
    }

    private static function tagAttributeNotFound($id, $tagName, $key)
    {
        return new \InvalidArgumentException(sprintf(
            'Service "%s" is tagged as "%s" but lacks required "%s" attribute.',
            $id,
            $tagName,
            $key
        ));
    }
}
