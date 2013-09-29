<?php

namespace Exercise\GigyaBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Exercise\GigyaBundle\DependencyInjection\Compiler\SecurityCompilerPass;

class ExerciseGigyaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SecurityCompilerPass());
    }
}
