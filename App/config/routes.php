<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use BadBox\Modules\Router;

return static function (Router $router) {

    $router->addRoute(
        '/',
        [HomeController::class,
         'show']
    )
           ->addRoute(
               '/login',
               [AuthController::class,
                'login']
           )
           ->addRoute(
               '/register',
               [AuthController::class,
                'register']
           );
};
