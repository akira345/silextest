<?php
namespace Testapp\ControllerProvider;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

/**
 * Class FrontControllerProvider
 * @package Testapp\ControllerProvider
 */
class FrontControllerProvider implements ControllerProviderInterface
{
    /**
     * @param Application $app
     * @return mixed
     */
    public function connect(Application $app)
    {
        $c = $app['controllers_factory'];
        //ルーティング
        $c->match('/','\Testapp\Controller\DefaultController::index');
        $c->match('/hello/{name}','\Testapp\Controller\HelloController::index');

        $c->match('/admin/','\Testapp\Controller\AdminController::index');
        $c->match('/admin/login','\Testapp\Controller\AdminController::login');


        return $c;
    }
}