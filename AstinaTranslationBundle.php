<?php

namespace Astina\Bundle\TranslationBundle;

use Astina\Bundle\TranslationBundle\DependencyInjection\Compiler\TranslatorResourcesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AstinaTranslationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TranslatorResourcesPass());
    }

}
