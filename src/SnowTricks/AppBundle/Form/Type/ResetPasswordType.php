<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/04/2018
 * Time: 12:56
 */

namespace SnowTricks\AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, array(
            'constraints' =>
                [
                    new NotBlank(),
                    new Email()
                ]
        ))
            ->add('password', PasswordType::class, array(
                'constraints' =>
                    [
                        new NotBlank(),
                        new Regex(
                            [
                                'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$/',
                                'match' => true,
                                'message' => 'Your password must contain at least 8 characters string with at least one digit, one upper case letter, one lower case letter and one special symbol'
                            ]
                        )
                    ]

            ))
            ->add('reset', SubmitType::class, array('label' => 'Reset'));
    }

    public function getBlockPrefix()
    {
        return null;
    }
}
