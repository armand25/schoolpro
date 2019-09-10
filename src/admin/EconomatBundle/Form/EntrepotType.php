<?php

namespace admin\StockBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \admin\UserBundle\Types\TypeEtat;
class EntrepotType extends AbstractType
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
    private $stockBundle = 'adminStockBundle:';    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codeEntrepot')
            ->add('nomEntrepot')
            ->add('contactEntrepot')
            ->add('adresseEntrepot')
            ->add('seuilEntrepot')
                
            ->add('ville', 'entity', array(
                'class' => $this->stockBundle . 'Ville',
                'property' => 'nomVille',
                'multiple' => false,
                
                
                'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('m')
                            ->where('m.etatVille = ?0')
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
            'data_class' => 'admin\StockBundle\Entity\Entrepot'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_stockbundle_entrepot';
    }
}
