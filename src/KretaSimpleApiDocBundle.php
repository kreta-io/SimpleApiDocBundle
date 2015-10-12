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

namespace Kreta\SimpleApiDocBundle;

use Kreta\SimpleApiDocBundle\DependencyInjection\Compiler\ExtractorHandlerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Simple ApiDoc Bundle kernel class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class KretaSimpleApiDocBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ExtractorHandlerCompilerPass());
    }
}
