<?php

namespace SnowTricks\TrickBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class)
            ->add('category', EntityType::class, array(
                'class' => 'SnowTricks\TrickBundle\Entity\Category',
                'choice_label' => 'name'
            ))
            ->add('pictures', CollectionType::class, array(
                // each entry in the array will be an "email" field
                'entry_type' => PictureType::class,
                'allow_add' => true,
                'allow_delete' => true
                // these options are passed to each "email" type
            ))
            ->add('videos', CollectionType::class, array(
                // each entry in the array will be an "email" field
                'entry_type' => VideoType::class,
                'allow_add' => true,
                'allow_delete' => true
                // these options are passed to each "email" type
            ))
            ->add('frontPicture', FileType::class)
            ->add('add', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SnowTricks\TrickBundle\Entity\Trick'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'snowtricks_trickbundle_trick';
    }


}
