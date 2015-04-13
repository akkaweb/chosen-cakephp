<?php
use Cake\Routing\Router;

Router::plugin('Chosen', function ($routes) {
    $routes->fallbacks();
});
