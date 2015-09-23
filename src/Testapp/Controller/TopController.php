<?php
namespace Testapp\Controller;
use Testapp\Application;

Class TopController
{
	public function index(Application $app,$name)
	{
		return $app['twig']->render('index.twig',array('name'=> $name));
	}
}