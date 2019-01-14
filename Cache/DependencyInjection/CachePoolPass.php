<?php

namespace UxGood\Component\Cache\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CachePoolPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {

    }

    public static function getServiceProvider(ContainerBuilder $container, $name)
    {
        return '';
    }
}
