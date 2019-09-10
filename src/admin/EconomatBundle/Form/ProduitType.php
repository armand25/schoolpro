<?php

namespace admin\StockBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \admin\UserBundle\Types\TypeEtat;

class ProduitType extends AbstractType
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
    private $userBundle = 'adminStockBundle:';    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codeProduit', 'text', array('required' => TRUE ,'attr' => array('required' => TRUE , 'placeholder' => 'Code Produit', 'class'=> 'form-control')))
            ->add('nomProduit','text', array('required' => TRUE ,'attr' => array('required' => TRUE , 'placeholder' => 'Nom', 'class'=> 'form-control')))
            ->add('descriptionProduit','text', array('required' => TRUE ,'attr' => array('required' => TRUE , 'placeholder' => 'Description', 'class'=> 'form-control')))
            ->add('poidsProduit','text', array('required' => TRUE ,'attr' => array('required' => TRUE , 'placeholder' => 'Poids', 'class'=> 'form-control')))
            ->add('seuilProduit','text', array('required' => TRUE ,'attr' => array('required' => TRUE , 'placeholder' => 'Seuil', 'class'=> 'form-control')))
            ->add('fabricatProduit','text', array('required' => TRUE ,'attr' => array('required' => TRUE , 'placeholder' => 'Fabriquant', 'class'=> 'form-control')))
                
            ->add('fournisseur', 'entity', array(
                'class' => $this->userBundle . 'Fournisseur',
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
                        
            ->add('categorie', 'entity', array(
                'class' => $this->userBundle . 'CategorieProduit',
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

            ->add('imagepros', 'collection', array('type' => new ImageProduitType(),'allow_add'   => true))
            ->add('montanthtAchat', 'text', array('required' => TRUE ,'attr' => array('required' => TRUE , 'placeholder' => 'Montant achat CFA ', 'class'=> 'form-control')))
            ->add('montanthtVente','text', array('required' => TRUE ,'attr' => array('required' => TRUE , 'placeholder' => 'Montant vente CFA', 'class'=> 'form-control')))
				
            
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'admin\StockBundle\Entity\Produit'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_stockbundle_produit';
    }
}
