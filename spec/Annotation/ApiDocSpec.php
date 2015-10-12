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

namespace spec\Kreta\SimpleApiDocBundle\Annotation;

use PhpSpec\ObjectBehavior;

/**
 * Spec of Api Doc class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ApiDocSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(['statusCodes' => ['401' => 'Bad authorized']]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\SimpleApiDocBundle\Annotation\ApiDoc');
    }

    function it_extends_nelmio_api_doc()
    {
        $this->shouldHaveType('Nelmio\ApiDocBundle\Annotation\ApiDoc');
    }
}
