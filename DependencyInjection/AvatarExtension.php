<?php

namespace Stnw\AvatarBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AvatarExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if ($config['folder'] && null !== $config['folder']) {
            $container->setParameter('avatar.folder', $config['folder']);
        } else {
            $container->setParameter('avatar.folder', __DIR__.'/../Resources/img/face_tamplates');
        }
        if ($config['male_folders'] && null !== $config['male_folders']) {
            $container->setParameter('avatar.male_folders', $config['male_folders']);
        }
        if ($config['female_folders'] && null !== $config['female_folders']) {
            $container->setParameter('avatar.female_folders', $config['female_folders']);
        }
        if (null !== $config['manager']) {
            $container->setParameter('avatar.manager', $config['manager']);
        }

    }
}
