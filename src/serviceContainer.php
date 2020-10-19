<?php
/**
 * DI container
 *
 * holds service dependency definitions, and also resolves services.
 */

use App\Application;
use App\Services\FS\FS;
use App\Services\Menu\Menu;
use App\Services\Pinger\Pinger;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


$containerBuilder = new ContainerBuilder();

$containerBuilder->register('menu', Menu::class);
$containerBuilder->register('fs', FS::class);
$containerBuilder->register('pinger', Pinger::class);

$containerBuilder
    ->register('app', Application::class)
    ->addArgument(new Reference('menu'))
    ->addArgument(new Reference('fs'))
    ->addArgument(new Reference('pinger'))
;

