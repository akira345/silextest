<?php
namespace Testapp\Controller;
use Testapp\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
Class AdminController
{
	public function index(Application $app)
	{
		return "OK";
	}
	public function login(Application $app,Request $request)
	{
		$form = $app['form.factory']->createBuilder()
			->add('username','text',array(
									'data' =>$app['session']->get('_security.last_username')  //値や属性をセット。Twig側でも出来る。
									)
			)
			->add('password','password')
			->getForm();

	    return $app['twig']->render('Admin/login.twig', array(
	        'error'         => $app['security.last_error']($request),
//	        'last_username' => $app['session']->get('_security.last_username'),
			'form' => $form->createView(),
	    ));
	}
}