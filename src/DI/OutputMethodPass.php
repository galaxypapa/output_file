<?php

namespace App\DI;


use App\Service\File\OutputMethodManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class OutputMethodPass implements CompilerPassInterface
{
    /**
     * Add the output format instances into the array variable named as $outputMethods in OutputMethodManager
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(OutputMethodManager::class)) {
            return;
        }

        $definition = $container->findDefinition(OutputMethodManager::class);

        $taggedServices = $container->findTaggedServiceIds('app.file_output');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addOutputMethods', [new Reference($id)]);
        }
    }
}