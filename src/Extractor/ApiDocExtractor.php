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

namespace Kreta\SimpleApiDocBundle\Extractor;

use Doctrine\Common\Annotations\Reader;
use Kreta\SimpleApiDocBundle\Parser\ValidationParser;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Nelmio\ApiDocBundle\Extractor\ApiDocExtractor as BaseApiDocExtractor;
use Nelmio\ApiDocBundle\Util\DocCommentExtractor;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * Api Doc Extractor class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ApiDocExtractor extends BaseApiDocExtractor
{
    const ANNOTATION_CLASS = 'Kreta\\SimpleApiDocBundle\\Annotation';

    /**
     * The validation parser.
     *
     * @var \Kreta\SimpleApiDocBundle\Parser\ValidationParser
     */
    private $validationParser;

    /**
     * Constructor.
     *
     * @param ContainerInterface   $container            The container
     * @param RouterInterface      $router               The router
     * @param Reader               $reader               The reader
     * @param DocCommentExtractor  $commentExtractor     The comment extractor
     * @param ControllerNameParser $controllerNameParser Controller name parser
     * @param array                $handlers             Array that contains handlers
     * @param array                $annotationsProviders Annotation providers
     * @param ValidationParser     $validationParser     The validation parser
     */
    public function __construct(
        ContainerInterface $container,
        RouterInterface $router,
        Reader $reader,
        DocCommentExtractor $commentExtractor,
        ControllerNameParser $controllerNameParser,
        array $handlers,
        array $annotationsProviders,
        ValidationParser $validationParser
    ) {
        parent::__construct(
            $container,
            $router,
            $reader,
            $commentExtractor,
            $controllerNameParser,
            $handlers,
            $annotationsProviders
        );
        $this->validationParser = $validationParser;
    }

    /**
     * {@inheritdoc}
     */
    protected function extractData(ApiDoc $annotation, Route $route, \ReflectionMethod $method)
    {
        $annotation->setRoute($route);
        $annotation = $this->buildInput($annotation);
        $annotation = $this->buildOutput($annotation);
        $annotation = parent::extractData($annotation, $route, $method);
        $statusCodes = $annotation->toArray()['statusCodes'];
        if (!(array_key_exists(400, $statusCodes)) || $statusCodes[400][0] === null) {
            $validations = $this->validationParser->getValidations(
                str_replace(['Interfaces\\', 'Interface'], '', $annotation->getOutput())
            );
            if (count($validations) > 0) {
                $annotation->addStatusCode(400, $validations);
            }
        }

        return $annotation;
    }

    /**
     * Method that adds the input property of ApiDoc
     * getting the form type's fully qualified name.
     *
     * @param ApiDoc     $annotation The annotation
     * @param array|null $data       The data given
     *
     * @return ApiDoc
     */
    public function buildInput(ApiDoc $annotation, $data = null)
    {
        $annotationReflection = new \ReflectionClass('Nelmio\ApiDocBundle\Annotation\ApiDoc');
        $inputReflection = $annotationReflection->getProperty('input');
        $inputReflection->setAccessible(true);

        if (!(isset($data['input']) || $data['input'] === null) || empty($data['input'])) {
            if ($annotation->toArray()['method'] === 'POST' || $annotation->toArray()['method'] === 'PUT') {
                $actionName = $annotation->getRoute()->getDefault('_controller');
                $controllerClass = substr($actionName, 0, strpos($actionName, '::'));
                $reflectionClass = new \ReflectionClass(substr($controllerClass, strpos($controllerClass, ':')));
                $class = str_replace('Controller', '', $reflectionClass->getShortName());
                $inputType = 'Kreta\\Component\\' . $class . '\\Form\\Type\\' . $class . 'Type';
                if (class_exists($inputType)) {
                    $inputReflection->setValue($annotation, $inputType);
                }
            }
        } else {
            $inputReflection->setValue($annotation, $data['input']);
        }

        return $annotation;
    }

    /**
     * Method that adds the output property of ApiDoc
     * getting the model's fully qualified name.
     *
     * @param ApiDoc     $annotation The annotation
     * @param array|null $data       The data given
     *
     * @return ApiDoc
     */
    public function buildOutput(ApiDoc $annotation, $data = null)
    {
        $annotationReflection = new \ReflectionClass('Nelmio\ApiDocBundle\Annotation\ApiDoc');
        $outputReflection = $annotationReflection->getProperty('output');
        $outputReflection->setAccessible(true);
        if (!(isset($data['output']) || $data['output'] === null) || empty($data['output'])) {
            if ($annotation->toArray()['method'] === 'POST' || $annotation->toArray()['method'] === 'PUT') {
                $actionName = $annotation->getRoute()->getDefault('_controller');
                $reflectionMethod = new \ReflectionMethod($actionName);
                $phpdoc = $reflectionMethod->getDocComment();
                $return = substr($phpdoc, strpos($phpdoc, '@return'));
                $returnType = str_replace(['@return \\', '*', '[]', 'array<\\', '>'], '', $return);
                $returnType = substr($returnType, 0, strpos($returnType, strstr($returnType, "\n")));
                $returnType = preg_replace("/\r\n|\r|\n|\\/\\s+/", '', $returnType);

                if (class_exists($returnType) || interface_exists($returnType)) {
                    $outputReflection->setValue($annotation, $returnType);
                }
            }
        } else {
            $outputReflection->setValue($annotation, $data['output']);
        }

        return $annotation;
    }
}
