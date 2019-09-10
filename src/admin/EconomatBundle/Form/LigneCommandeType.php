<?php

namespace admin\StockBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LigneCommandeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    
    private $stockBundle = 'adminUserBundle:';
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantite')
            ->add('etatLigneCommande')                
            ->add('CategorieProduit', 'entity', array(
                'class' => $this->stockBundle . 'CategorieProduit',
                'property' => 'nom',
                'multiple' => false,
                                
                'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('m')
                            ->where('m.etat = ?0')
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
            'data_class' => 'admin\StockBundle\Entity\LigneCommande'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_stockbundle_lignecommande';
    }
}
