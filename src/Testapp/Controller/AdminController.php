<?php
namespace Testapp\Controller;
use Testapp\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Testapp\Form\Type\Admin\LoginType;


/**
 * Class AdminController
 * @package Testapp\Controller
 */
Class AdminController
{
    public function index(Application $app)
    {
        return $app['twig']->render('Admin/index.twig');
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return mixed
     */
    public function login(Application $app, Request $request)
    {

        $form = $app['form.factory']->createNamedBuilder('', LoginType::class)
            ->getForm();

        return $app['twig']->render('Admin/login.twig', array(
            'error'         => $app['security.last_error']($request),
            //'last_username' => $app['session']->get('_security.last_username'),
            'form' => $form->createView(),
        ));

    }
}