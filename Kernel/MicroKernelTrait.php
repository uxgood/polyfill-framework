<?php

namespace UxGood\Bundle\FrameworkBundle\Kernel;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait as BaseMicroKernelTrait;

use Symfony\Component\HttpKernel\DependencyInjection\AddAnnotatedClassesToCachePass;

trait MicroKernelTrait
{
    use BaseMicroKernelTrait;

    protected function buildContainer()
    {
        $container = $this->getContainerBuilder();
        $container->addObjectResource($this);
        $this->prepareContainer($container);

        if (null !== $cont = $this->registerContainerConfiguration($this->getContainerLoader($container))) {
            $container->merge($cont);
        }

        $container->addCompilerPass(new AddAnnotatedClassesToCachePass($this));

        return $container;
    }

    protected function initializeContainer()
    {
        $this->container = $this->buildContainer();
        $this->container->compile();
        $this->container->set('kernel', $this);
    }
}
