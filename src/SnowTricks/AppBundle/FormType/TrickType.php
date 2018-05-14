<?php

namespace SnowTricks\AppBundle\FormType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('name', TextType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Trick name'
            )))
            ->add('description', TextareaType::class, array(
                'label' => false,
                'attr' => array(
                    'rows' => 15,
                    'placeholder' => 'Describe your trick'
            )))
            ->add('category', EntityType::class, array(
                'label' => false,
                'class' => 'SnowTricks\AppBundle\Entity\Category',
                'choice_label' => 'name'
            ))
            ->add('pictures', CollectionType::class, array(
                'label' => false,
                'entry_type' => PictureType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('videos', CollectionType::class, array(
                'label' => false,
                'entry_type' => VideoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('frontPicture', FileType::class, array(
                'label' => false,
                'data_class' => null,
                'required' => false
            ))
            ->add('frontPictureName', HiddenType::class)
            ->add('Save', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SnowTricks\AppBundle\Entity\Trick',
            'attr'=>array('novalidate'=>'novalidate')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'snowtricks_appbundle_trick';
    }
}
