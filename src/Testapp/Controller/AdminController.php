<?php
namespace Testapp\Controller;
use Testapp\Application;
use Symfony\Component\HttpFoundation\Request;

Class AdminController
{
	public function index(Application $app)
	{
		return "OK";
	}
	public function login(Application $app,Request $request)
	{
	    return $app['twig']->render('login.twig', array(
	        'error'         => $app['security.last_error']($request),
	        'last_username' => $app['session']->get('_security.last_username'),
	    ));
	}
}