<?php

namespace admin\EconomatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CaisseType extends AbstractType
{
     use \admin\UserBundle\ControllerModel\stockTrait;
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomCaisse')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'admin\EconomatBundle\Entity\Caisse'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_economatbundle_caisse';
    }
}
