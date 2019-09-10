<?php

namespace admin\ParamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Core\Type\FileType;

class AttributType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           
            ->add('nomAttribut','text', array('label'=>'Nom'))
            ->add('caractereAttrib','text', array('label'=>'Caractere'))
            ->add('affichAttrib','text', array('label'=>'Afficher'))
            ->add('balise', 'entity', array(
                'class' => 'adminParamBundle:Balise',
                'property' => 'nomBalise',
                'multiple' => false,
                'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('m')
                            ;
                    return $qb;
                },
                  'required' => TRUE,
                  'attr' => array('required' => TRUE, 'class' => 'form-control')))                
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'admin\ParamBundle\Entity\Attribut'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_parambundle_balise';
    }
}
