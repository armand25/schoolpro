<?php

namespace admin\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MediaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomMedia')
            ->add('file', FileType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'class' => 'form-control')))
            
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'admin\CmsBundle\Entity\Media'
        ));
    }
 /**
     * @return string
     */
    public function getName()
    {
        return 'admin_cmsbundle_media';
    }   
    
}
