<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/04/2018
 * Time: 11:05
 */

namespace SnowTricks\AppBundle\Form;

use SnowTricks\AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;


class DashboardType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email', EmailType::class)
            ->add('picture', FileType::class, array(
                'required' => false,
                'label' => 'Update your avatar',
                'data_class' => null
            ))
            ->add('register', SubmitType::class, array('label' => 'Update my account'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }

}