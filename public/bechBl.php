<?php

declare(strict_types=1);

// Delegate static file requests back to the PHP built-in webserver
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * Self-called anonymous function that creates its own scope and keep the global namespace clean.
 */
(function () {
    /** @var \Psr\Container\ContainerInterface $container */
    $container = require 'config/container.php';

    /** @var \BuhoLegalSearch\Service\BuhoLegalClient $blClient */
    $blClient = $container->get(\BuhoLegalSearch\Service\BuhoLegalClient::class);

    for($x=0;$x<=5;$x++) {
        $personData=['name'=>"PABLO",'lastName'=>"HERNANDEZ",'secondLastName'=>"GARCIA"];
        $blClient->buhoLegalSearch($personData,"aproximado");
        $blClient->buhoLegalSearch($personData,"aproximado");
        $blClient->buhoLegalSearch($personData,"aproximado");
        $blClient->buhoLegalSearch($personData,"aproximado");
        $blClient->buhoLegalSearch($personData,"aproximado");
        echo "durmiendo...";
        flush();
        sleep(10);
        echo "desperté!!";
        flush();
    }

})();
