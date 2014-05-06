<?php

namespace Astina\Bundle\TranslationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TranslatorResourcesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $config = $container->getExtensionConfig('astina_translation');
        if (empty($config)) {
            return;
        }

        $domains = $config[0]['domains'];
        $locales = $config[0]['locales'];

        $translator = $container->getDefinition('translator.default');

        foreach ($domains as $domain) {
            foreach ($locales as $locale) {
                $translator->addMethodCall('addResource', array(
                    'db', null, $locale, $domain
                ));
            }
        }
    }
}