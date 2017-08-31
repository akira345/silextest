<?php

namespace Testapp\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class LoginType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //ログインフォーム
        $builder->add('username', TextType::class, array(
            'attr' => array(
                'max_length' => 50,
            ),
            'constraints' => array(
                new Assert\NotBlank(),
            ),
        ));
        $builder->add('password', PasswordType::class, array(
            'attr' => array(
                'max_length' => 50,
            ),
            'constraints' => array(
                new Assert\NotBlank(),
            ),
        ));
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'admin_login';
    }
}
