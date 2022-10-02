#!/usr/bin/env php
<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Console\Application;
use App\App;

require __DIR__.'/vendor/autoload.php';

require __DIR__.'/vendor/league/csv/autoload.php';

$container = new ContainerBuilder();

// Load container configuration
$loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config'));
$loader->load('services.yml');

// Compile container
$container->compile();

// Start the console application.
exit($container->get(App::class)->run());