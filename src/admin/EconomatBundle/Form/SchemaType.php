<?php

namespace admin\EconomatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
class SchemaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder               
            ->add('typeoperation',EntityType::class, array(
                      'class'    => 'utbClientBundle:TypeOperation',
                      'choice_label' => 'libTypeOperation',
                      'multiple' => false,
                      'expanded' => false)) 
            ->add('coef',TextType::class, array('label'=>'Libellé Type Operation'))   ;

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'admin\EconomatBundle\Entity\Schema'
        ));
    }

    public function getName()
    {
        return 'admin_economatbundle_schematype';
    }
}
