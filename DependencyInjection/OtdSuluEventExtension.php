<?php

declare(strict_types = 1);

namespace Otd\SuluEventBundle\DependencyInjection;

use Otd\SuluEventBundle\Entity\Event;
use Sulu\Bundle\PersistenceBundle\DependencyInjection\PersistenceExtensionTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class OtdSuluEventExtension extends Extension implements PrependExtensionInterface
{
    use PersistenceExtensionTrait;

    /**
     * Allow an extension to prepend the extension configurations.
     */
    public function prepend(ContainerBuilder $container): void
    {
        if ($container->hasExtension('sulu_route')) {
            $container->prependExtensionConfig(
                'sulu_route',
                [
                    'mappings' => [
                        Event::class => [
                            'generator' => 'schema',
                            'options' => ['route_schema' => '/events/{object.getId()}'],
                            'resource_key' => Event::RESOURCE_KEY,
                        ],
                    ],
                ],
            );
        }

        if ($container->hasExtension('sulu_admin')) {
            $container->prependExtensionConfig(
                'sulu_admin',
                [
                    'lists' => [
                        'directories' => [
                            __DIR__ . '/../Resources/config/lists',
                        ],
                    ],
                    'forms' => [
                        'directories' => [
                            __DIR__ . '/../Resources/config/forms',
                        ],
                    ],
                    'resources' => [
                        'events' => [
                            'routes' => [
                                'list' => 'otd_sulu.get_events',
                                'detail' => 'otd_sulu.get_event',
                            ],
                        ],
                    ],
                ],
            );
        }

        $container->loadFromExtension('framework', [
            'default_locale' => 'fr',
            'translator' => ['paths' => [__DIR__ . '/../Resources/config/translations']],
            // ...
        ]);
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config'),
        );

        $loader->load('services.xml');
    }
}
