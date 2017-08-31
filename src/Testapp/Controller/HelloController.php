<?php
namespace Testapp\Controller;
use Testapp\Application;

/**
 * Class HelloController
 * @package Testapp\Controller
 */
Class HelloController
{
    /**
     * @param Application $app
     * @param $name
     * @return mixed
     */
    public function index(Application $app, $name)
    {
        return $app['twig']->render('Hello/index.twig',array('name'=> $name));
    }
}