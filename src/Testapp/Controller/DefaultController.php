<?php
namespace Testapp\Controller;
use Testapp\Application;

Class DefaultController
{
    public function index(Application $app)
    {
        return $app['twig']->render('Default/index.twig');
    }
}