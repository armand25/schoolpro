<?php

namespace admin\ParamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfigType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nomConnexion', 'text', array('required' => TRUE ,'attr' => array('required' => TRUE, 'class' => 'form-control')))
                
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'admin\ParamBundle\Entity\Config'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'admin_adminbundle_config';
    }

}
