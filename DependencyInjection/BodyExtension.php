<?php

/**
 * This file is part of shopery/body-bundle
 *
 * Copyright (c) 2016 Shopery.com
 */

namespace Shopery\Bundle\BodyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class BodyExtension extends Extension
{
    public function getAlias()
    {
        return 'shopery_body';
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        if ($this->supportsArgumentResolver()) {
            $loader->load('argument_resolver.yml');
        } else {
            $loader->load('param_converter.yml');
        }
    }

    private function supportsArgumentResolver()
    {
        if (!interface_exists('Symfony\\Component\\HttpKernel\\Controller\\ArgumentValueResolverInterface')) {
            return false;
        }

        // Checks support of yield keyword (PHP < 5.5)
        return defined('T_YIELD');
    }
}
