<?php

namespace admin\StockBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \admin\UserBundle\Types\TypeEtat;

class CommandeTmpType extends AbstractType
{
    
    private $typeCom;
    
    protected $stockBundle = 'adminStockBundle:';
    
    private $userBundle = 'adminUserBundle:';

    public function __construct() {

    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codeCommande')
            ->add('fournisseur', 'entity', array(
                'class' => $this->stockBundle . 'Fournisseur',
                'property' => 'nomFournisseur',
                'multiple' => false,
                
                
                'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('m')
                            ->where('m.etatFournisseur = ?0')
                            ->setParameters(array(0 => TypeEtat::ACTIF));
                    return $qb;
                },
                        
                'required' => TRUE,
                'attr' => array('required' => TRUE, 'class' => 'form-control')))
            ->add('descriptionCommande')
            ->add('refBonCommande')
                            
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'admin\StockBundle\Entity\CommandeTmp'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_stockbundle_commandetmp';
    }
}
