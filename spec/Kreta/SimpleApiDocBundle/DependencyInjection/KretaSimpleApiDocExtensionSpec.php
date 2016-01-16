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

namespace spec\Kreta\SimpleApiDocBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Spec of Kreta Simple Api Doc Extension class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class KretaSimpleApiDocExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\SimpleApiDocBundle\DependencyInjection\KretaSimpleApiDocExtension');
    }

    function it_extends_extension()
    {
        $this->shouldHaveType('Symfony\Component\DependencyInjection\Extension\Extension');
    }

    function it_builds(ContainerBuilder $container)
    {
        $this->load([], $container);
    }
}
