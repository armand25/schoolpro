<?php

namespace admin\EconomatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \admin\UserBundle\Types\TypeEtat;
class VilleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    /**
     * 
     * @var string  $userBundle
     * Nom du Bundle
     */
     use \admin\UserBundle\ControllerModel\stockTrait;     
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomVille')
            ->add('pays', 'entity', array(
                'class' => $this->stockBundle . 'Pays',
                'property' => 'nomPays',
                'multiple' => false,
                
                
                'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('m')
                            ->where('m.etatPays = ?0')
                            ->setParameters(array(0 => TypeEtat::ACTIF));
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
            'data_class' => 'admin\EconomatBundle\Entity\Ville'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_economatbundle_ville';
    }
}
