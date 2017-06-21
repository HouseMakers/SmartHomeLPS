<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->formsDir
    ]
);

$loader->registerNamespaces([
    'SmartHomeLPS\Services' => $config->application->servicesDir,
    'SmartHomeLPS\Services\SmartHome' => $config->application->servicesDir . "smarthome/"
]);

$loader->registerFiles([APP_PATH . '/vendor/aws/aws-autoloader.php']);


$loader->register();