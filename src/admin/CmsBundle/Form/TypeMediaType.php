<?php

namespace admin\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;



class TypeMediaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelleTypeMedia',TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Libellé du type de media *', 'class' => 'form-control')))
            ->add('descriptionTypeMedia',TextareaType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Description du type de media', 'class' => 'form-control')))    
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'admin\CmsBundle\Entity\TypeMedia'
        ));
    }
}
