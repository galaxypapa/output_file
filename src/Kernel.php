<?php

namespace App;

use App\DI\DataRulePass;
use App\DI\OutputMethodPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new OutputMethodPass());
        $container->addCompilerPass(new DataRulePass());
    }
}
