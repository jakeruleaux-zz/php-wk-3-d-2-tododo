<?php
    date_default_timezone_set("America/Los_Angeles");
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Task.php"

    $app = new Silex\Application();

    $app->get("/", function() {

    });
?>
