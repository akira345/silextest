<?php
namespace Testapp\Controller;
use Testapp\Application;

/**
 * Class DefaultController
 * @package Testapp\Controller
 */
Class DefaultController
{
    /**
     * @param Application $app
     * @return mixed
     */
    public function index(Application $app)
    {
        return $app['twig']->render('Default/index.twig');
    }
}