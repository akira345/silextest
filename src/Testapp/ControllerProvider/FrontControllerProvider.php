<?php
namespace Testapp\ControllerProvider;
use Silex\Application;
use Silex\ControllerProviderInterface;

class FrontControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $c = $app['controllers_factory'];
        //ルーティング
        $c->match('/','\Testapp\Controller\DefaultController::index');
        $c->match('/hello/{name}','\Testapp\Controller\TopController::index');

        $c->match('/admin/','\Testapp\Controller\AdminController::index');
        $c->match('/admin/login','\Testapp\Controller\AdminController::login');


        return $c;
    }
}