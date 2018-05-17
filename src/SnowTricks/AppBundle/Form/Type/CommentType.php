<?php

namespace SnowTricks\AppBundle\Form\Type;

use SnowTricks\AppBundle\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', TextareaType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Your comment',
                    'rows' => 2
            )))
            ->add('Leave a comment', SubmitType::class, array(
                'attr' => array(
                    'class' => 'mt-3'
                )
            ));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Comment::class,
            'attr'=>array('novalidate'=>'novalidate')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'snowtricks_appbundle_comment';
    }
}
