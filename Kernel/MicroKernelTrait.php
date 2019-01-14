<?php

namespace UxGood\Bundle\FrameworkBundle\Kernel;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait as BaseMicroKernelTrait;

//use Symfony\Component\HttpKernel\DependencyInjection\AddAnnotatedClassesToCachePass;

use Symfony\Component\Cache\DependencyInjection\CachePoolPass;
use Symfony\Component\Cache\DependencyInjection\CachePoolClearerPass;
use Symfony\Component\Cache\DependencyInjection\CachePoolPrunerPass;
use Symfony\Component\Cache\DependencyInjection\CacheCollectorPass;

trait MicroKernelTrait
{
    use BaseMicroKernelTrait;

    protected function buildContainer()
    {
        $container = $this->getContainerBuilder();
        $container->addObjectResource($this);
        $this->loadClassAlias();
        $this->prepareContainer($container);

        if (null !== $cont = $this->registerContainerConfiguration($this->getContainerLoader($container))) {
            $container->merge($cont);
        }

        //$container->addCompilerPass(new AddAnnotatedClassesToCachePass($this));

        return $container;
    }

    protected function initializeContainer()
    {
        $this->container = $this->buildContainer();
        $this->container->compile();
        $arg = $this->container->getDefinition('router')->getArgument(2);
        $arg['cache_dir'] = null;
        $arg['generator_cache_class'] = null;
        $arg['matcher_cache_class'] = null;
        $this->container->getDefinition('router')->replaceArgument(2, $arg);
        $this->container->set('kernel', $this);
    }

    protected function loadClassAlias()
    {
        foreach(array(CachePoolPass::class,CachePoolClearerPass::class,CachePoolPrunerPass::class,CacheCollectorPass::class) as $class_name) {
            if(!class_exists($class_name)) {
                class_alias(str_replace('Symfony\\', 'UxGood\\', $class_name), $class_name);
            }
        }
    }
}
