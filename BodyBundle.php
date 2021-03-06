<?php

/**
 * This file is part of shopery/body-bundle
 *
 * Copyright (c) 2016 Shopery.com
 */

namespace Shopery\Bundle\BodyBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use Shopery\Bundle\BodyBundle\DependencyInjection\CompilerPass\DecoderCollector;

class BodyBundle extends Bundle
{
    public function __construct()
    {
        $this->name = 'ShoperyBodyBundle';
    }

    public function createContainerExtension()
    {
        return new DependencyInjection\BodyExtension();
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DecoderCollector());
    }
}
