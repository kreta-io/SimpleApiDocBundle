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

namespace Kreta\SimpleApiDocBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Api doc controller class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ApiDocController extends Controller
{
    /**
     * The index action.
     *
     * @return Response
     */
    public function indexAction()
    {
        $extractedDoc = $this->get('kreta_simple_api_doc.extractor.api_doc_extractor')->all();
        $htmlContent = $this->get('nelmio_api_doc.formatter.html_formatter')->format($extractedDoc);

        return new Response($htmlContent, 200, ['Content-Type' => 'text/html']);
    }
}
