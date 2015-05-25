<?php

/*
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\SimpleApiDocBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Nelmio\ApiDocBundle\DependencyInjection\ExtractorHandlerCompilerPass as BaseExtractorHandlerCompilerPass;

/**
 * Class ExtractorHandlerCompilerPass.
 *
 * @package Kreta\SimpleApiDocBundle\DependencyInjection\Compiler
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
