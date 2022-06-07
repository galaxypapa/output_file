<?php

namespace App\DI;

use App\Service\Rule\ValidatorManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DataRulePass implements CompilerPassInterface
{
    /**
     * Add the Validator instances into the array variable named as $validators in ValidatorManager
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(ValidatorManager::class)) {
            return;
        }

        $definition = $container->findDefinition(ValidatorManager::class);

        $taggedServices = $container->findTaggedServiceIds('app.data_rule');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addValidators', [new Reference($id)]);
        }
    }
}