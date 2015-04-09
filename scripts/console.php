<?php
use vendor\Core\App\Service\ServiceContainer;
$dir = dirname(dirname(__FILE__));

include $dir . '/config/config.php';
include $dir .'/vendor/autoload.php';

$container = new ServiceContainer(SERVICES_FILE);
$container->registerServices([
    "vendor\\Core\\View\\ViewRenderer",
    "vendor\\Core\\Routing\\Router"
]);


echo implode("\n", ['Here are all the services from application', str_repeat('-', 30)]). PHP_EOL;



foreach($container->getRegistered() as $name => $service){

    if ($name !== 'router') continue;

    var_dump($service->hasDependencies());


    //echo sprintf("%-25s %s\n", $service->getName(), $service->getClass());
}

print_r($container->get('router'));

//print_r($container->get('acme.service')->hello());



























