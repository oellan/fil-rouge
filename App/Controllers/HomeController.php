<?php

namespace App\Controllers;

use BadBox\Http\AbstractController;
use BadBox\Http\Response\Response;
use BadBox\Http\Response\TemplateResponse;

class HomeController
    extends AbstractController
{

    public static function show(): Response
    {
        return new TemplateResponse("home.html");
    }
}
