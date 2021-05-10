<?php

declare(strict_types=1);

namespace App\DependencyInjection\Compiler;

use App\Generator\CsvContentGenerator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class QuizControllerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $container
            ->getDefinition('app.controller.quiz')
            ->addMethodCall('setCsvContentGenerator', [new Reference(CsvContentGenerator::class)]);
    }
}
