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

namespace spec\Kreta\SimpleApiDocBundle\Controller;

use Kreta\SimpleApiDocBundle\Extractor\ApiDocExtractor;
use Nelmio\ApiDocBundle\Formatter\HtmlFormatter;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Spec of Api Doc controller class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ApiDocControllerSpec extends ObjectBehavior
{
    function let(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\SimpleApiDocBundle\Controller\ApiDocController');
    }

    function it_extends_controller()
    {
        $this->shouldHaveType('Symfony\Bundle\FrameworkBundle\Controller\Controller');
    }

    function it_index_action(
        ContainerInterface $container,
        ApiDocExtractor $apiDocExtractor,
        HtmlFormatter $htmlFormatter
    ) {
        $container->get('kreta_simple_api_doc.extractor.api_doc_extractor')
            ->shouldBeCalled()->willReturn($apiDocExtractor);
        $apiDocExtractor->all()->shouldBeCalled()->willReturn([]);
        $container->get('nelmio_api_doc.formatter.html_formatter')->shouldBeCalled()->willReturn($htmlFormatter);
        $htmlFormatter->format([])->shouldBeCalled()->willReturn('formatted result');

        $this->indexAction()->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
