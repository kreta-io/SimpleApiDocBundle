<?php

/*
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\SimpleApiDocBundle\Extractor;

use Doctrine\Common\Annotations\Reader;
use Kreta\SimpleApiDocBundle\Parser\ValidationParser;
use ReflectionMethod;
use Symfony\Component\Routing\Route;
use Nelmio\ApiDocBundle\Extractor\ApiDocExtractor as BaseApiDocExtractor;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Nelmio\ApiDocBundle\Util\DocCommentExtractor;

/**
 * Class ApiDocExtractor.
 *
 * @package Kreta\SimpleApiDocBundle\Extractor
 */
class ApiDocExtractor extends BaseApiDocExtractor
{
    /**
     * The validation parser.
     *
     * @var \Kreta\SimpleApiDocBundle\Parser\ValidationParser
     */
    private $validationParser;

    const ANNOTATION_CLASS = 'Kreta\\SimpleApiDocBundle\\Annotation';

    /**
     * Constructor.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container        The container
     * @param \Symfony\Component\Routing\RouterInterface                $router           The router
     * @param \Doctrine\Common\Annotations\Reader                       $reader           The reader
     * @param \Nelmio\ApiDocBundle\Util\DocCommentExtractor             $commentExtractor The comment extractor
     * @param array                                                     $handlers         Array that contains handlers
     * @param \Kreta\SimpleApiDocBundle\Parser\ValidationParser         $validationParser The validation parser
     */
    public function __construct(
        ContainerInterface $container,
        RouterInterface $router,
        Reader $reader,
        DocCommentExtractor $commentExtractor,
        array $handlers,
        ValidationParser $validationParser
    )
    {
        parent::__construct($container, $router, $reader, $commentExtractor, $handlers);
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
     * Method that adds the input property of ApiDoc getting the form type's fully qualified name.
     *
     * @param \Nelmio\ApiDocBundle\Annotation\ApiDoc $annotation The annotation
     * @param array|null                             $data       The data given
     *
     * @return \Nelmio\ApiDocBundle\Annotation\ApiDoc
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
     * Method that adds the output property of ApiDoc getting the model's fully qualified name.
     *
     * @param \Nelmio\ApiDocBundle\Annotation\ApiDoc $annotation The annotation
     * @param array|null                             $data       The data given
     *
     * @return \Nelmio\ApiDocBundle\Annotation\ApiDoc
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
