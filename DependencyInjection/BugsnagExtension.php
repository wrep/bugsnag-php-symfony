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
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $config = $this->mergeConfigs($configs);

        if (empty($config['api_key'])) {
            return;
        }

        $container->setParameter('bugsnag.api_key', $config['api_key']);
    }

    /**
     * Merges the given configurations
     *
     * @param  array $configs An array of configurations
     * @return array The merged configuration
     */
    public function mergeConfigs(array $configs)
    {
        $merged = array();

        foreach ($configs as $config) {
            foreach ($config as $key => $value) {
                $merged[$key] = $value;
            }
        }

        return $merged;
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
