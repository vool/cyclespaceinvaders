<?php

namespace CycleSpaceInvaders\Controllers;

class ErrorController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function notFound()//: string
    {

        header('HTTP/1.1 404 Not Found');
        echo $this->tpl->render('errors::404', ['error' => 'Page not found :(']);

        return false;

    }
}
