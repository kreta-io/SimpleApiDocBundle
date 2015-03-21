<?php

/*
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\SimpleApiDocBundle\Parser;

use Nelmio\ApiDocBundle\Parser\ValidationParser as BaseValidationParser;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\MetadataFactoryInterface;
use Symfony\Component\Validator\ValidatorInterface;

/**
 * Class ValidationParser.
 *
 * @package Kreta\SimpleApiDocBundle\Parser
 */
class ValidationParser extends BaseValidationParser
{
    /**
     * The Symfony validator component.
     *
     * @var \Symfony\Component\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * Constructor.
     *
     * @param MetadataFactoryInterface                        $factory   The metadata factory
     * @param \Symfony\Component\Validator\ValidatorInterface $validator The Symfony validator component
     */
    public function __construct(MetadataFactoryInterface $factory, ValidatorInterface $validator)
    {
        parent::__construct($factory);
        $this->validator = $validator;
    }

    public function getValidations($class = null)
    {
        $reflectionClass = $class ? new \ReflectionClass($class) : false;
        if ($reflectionClass === false) {
            return [];
        }
        $result = $this->buildValidation($class);
        $validations = [];
        foreach ($result as $keyElement => $element) {
            if (is_array($element)) {
                foreach ($element as $keyChild => $child) {
                    if (is_array($child)) {
                        foreach ($child as $grandChild) {
                            $validations[] = sprintf('%s => %s', $keyChild, $grandChild);
                        }
                    } else {
                        $validations[] = sprintf('%s => %s', $keyElement, $child);
                    }
                }
            } else {
                $validations[] = $element;
            }
        }

        return $validations;
    }

    protected function buildValidation($class = null)
    {
        $validations = [];
        $metadata = $this->validator->getMetadataFor($class);
        $entityConstraints = [];
        foreach ($metadata->getConstraints() as $constraint) {
            $class = new \ReflectionClass($constraint);
            $entityConstraint = [implode(', ', $constraint->fields) => $constraint->message];
            $entityConstraints = array_merge($entityConstraints, $entityConstraint);
            $validations[$class->getShortName()] = $entityConstraint;
        }

        foreach ($metadata->getConstrainedProperties() as $property) {
            $constraints = $metadata->getPropertyMetadata($property)[0]->constraints;
            $result = [];
            foreach ($constraints as $constraint) {
                $class = new \ReflectionObject($constraint);
                switch ($name = $class->getShortName()) {
                    case 'True':
                        $result[$name] = $constraint->message;
                        break;
                    case 'NotBlank':
                        $result[$name] = $constraint->message;
                        break;
                    case 'Type':
                        $result[$name] = $constraint->type;
                        break;
                    case 'Length':
                        $result[$name] = $this->constraintMessages($constraint);
                        break;
                    case 'Count':
                        $result[$name] = $this->constraintMessages($constraint);
                        break;
                }
            }
            $validations[$property] = $result;
        }

        return $validations;
    }

    protected function constraintMessages(Constraint $constraint)
    {
        $result = [];
        if (isset($constraint->min) && $constraint->min !== null) {
            $count = new Count(['min' => $constraint->min]);
            $length = new Length(['min' => $constraint->min]);
            $result[] = $constraint->minMessage;
        }
        if (isset($constraint->max) && $constraint->max !== null) {
            $count = new Count(['max' => $constraint->min]);
            $length = new Length(['max' => $constraint->min]);
            $result[] = $constraint->maxMessage;
        }
        if (isset($constraint->exactMessage)
            && $constraint->exactMessage !== $count->exactMessage
            && $constraint->exactMessage !== $length->exactMessage
        ) {
            $result[] = $constraint->exactMessage;
        }

        return $result;
    }
}
