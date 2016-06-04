<?php

/**
 * This file is part of shopery/body-bundle
 *
 * Copyright (c) 2016 Shopery.com
 */

namespace Shopery\Bundle\BodyBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

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
}
