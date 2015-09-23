<?php
namespace Testapp\ControllerProvider;
use Silex\Application;
use Silex\ControllerProviderInterface;

class FrontControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$c = $app['controllers_factory'];
		$c->match('/hello','\Testapp\Controller\TopController::index');

		return $c;
	}
}