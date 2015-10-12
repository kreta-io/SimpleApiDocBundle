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

namespace spec\Kreta\SimpleApiDocBundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Spec of Kreta Simple Api Doc kernel class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class KretaSimpleApiDocBundleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\SimpleApiDocBundle\KretaSimpleApiDocBundle');
    }

    function it_extends_bundle()
    {
        $this->shouldHaveType('Symfony\Component\HttpKernel\Bundle\Bundle');
    }

    function it_builds(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            Argument::type('Kreta\SimpleApiDocBundle\DependencyInjection\Compiler\ExtractorHandlerCompilerPass')
        )->shouldBeCalled()->willReturn($container);

        $this->build($container);
    }
}
