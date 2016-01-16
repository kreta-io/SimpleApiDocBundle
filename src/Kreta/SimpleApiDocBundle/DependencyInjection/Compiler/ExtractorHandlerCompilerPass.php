<?php

/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kreta\SimpleApiDocBundle\DependencyInjection\Compiler;

use Nelmio\ApiDocBundle\DependencyInjection\ExtractorHandlerCompilerPass as BaseExtractorHandlerCompilerPass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Extractor Handler Compiler Pass.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ExtractorHandlerCompilerPass extends BaseExtractorHandlerCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $handlers = [];
        foreach ($container->findTaggedServiceIds('nelmio_api_doc.extractor.handler') as $id => $attributes) {
            $handlers[] = new Reference($id);
        }
        $annotationProviders = [];
        foreach ($container->findTaggedServiceIds('nelmio_api_doc.extractor.annotations_provider') as $id => $attributes) {
            $annotationProviders[] = new Reference($id);
        }
        $container
            ->getDefinition('kreta_simple_api_doc.extractor.api_doc_extractor')
            ->replaceArgument(5, $handlers);
        $container
            ->getDefinition('kreta_simple_api_doc.extractor.api_doc_extractor')
            ->replaceArgument(6, $annotationProviders);
    }
}
