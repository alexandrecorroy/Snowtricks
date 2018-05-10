<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/04/2018
 * Time: 12:56
 */

namespace SnowTricks\AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class ForgotPasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, array(
            'constraints' => new Length(array('min' => 6))
        ))
            ->add('reset', SubmitType::class, array('label' => 'Ask for reset password'))
        ;
    }

    public function getBlockPrefix() {
        return null;
    }

}