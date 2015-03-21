<?php

/*
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\SimpleApiDocBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiDocController.
 *
 * @package Kreta\SimpleApiDocBundle\Controller
 */
class ApiDocController extends Controller
{
    /**
     * The index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $extractedDoc = $this->get('kreta_simple_api_doc.extractor.api_doc_extractor')->all();
        $htmlContent = $this->get('nelmio_api_doc.formatter.html_formatter')->format($extractedDoc);

        return new Response($htmlContent, 200, ['Content-Type' => 'text/html']);
    }
}
