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

namespace spec\Kreta\SimpleApiDocBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Spec of Extractor Handler Compiler Pass class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ExtractorHandlerCompilerPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\SimpleApiDocBundle\DependencyInjection\Compiler\ExtractorHandlerCompilerPass');
    }

    function it_extends_nelmio_extractor_handler_compiler_pass()
    {
        $this->shouldHaveType('Nelmio\ApiDocBundle\DependencyInjection\ExtractorHandlerCompilerPass');
    }

    function it_implements_compiler_pass_interface()
    {
        $this->shouldImplement('Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface');
    }

    function it_builds(ContainerBuilder $container, Definition $definition)
    {
        $container->findTaggedServiceIds('nelmio_api_doc.extractor.handler')
            ->shouldBeCalled()->willReturn(['id' => ['attr1', 'attr2']]);

        $container->findTaggedServiceIds('nelmio_api_doc.extractor.annotations_provider')
            ->shouldBeCalled()->willReturn(['id' => ['attr1', 'attr2']]);

        $container->getDefinition('kreta_simple_api_doc.extractor.api_doc_extractor')
            ->shouldBeCalled()->willReturn($definition);
        $definition->replaceArgument(
            5, [new Reference('id')]
        )->shouldBeCalled()->willReturn($definition);

        $container->getDefinition('kreta_simple_api_doc.extractor.api_doc_extractor')
            ->shouldBeCalled()->willReturn($definition);
        $definition->replaceArgument(
            6, [new Reference('id')]
        )->shouldBeCalled()->willReturn($definition);

        $this->process($container);
    }
}
