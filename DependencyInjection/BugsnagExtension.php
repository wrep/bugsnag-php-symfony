<?php

namespace Wrep\Bundle\BugsnagBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

class BugsnagExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
    	$configuration = new Configuration();
    	$config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        // Set api key
        $container->setParameter('bugsnag.api_key', $config['api_key']);

        // Set stages to notify from configuration, fall back on production only if not set
        if (is_array($config['notify_stages']))
        {
        	$container->setParameter('bugsnag.notify_stages', $config['notify_stages']);
        }
        else
        {
        	$container->setParameter('bugsnag.notify_stages', array('production'));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/schema';
    }

    /**
     * {@inheritDoc}
     */
    public function getNamespace()
    {
        return 'http://www.wrep.nl/schema/dic/bugsnag_bundle';
    }
}
