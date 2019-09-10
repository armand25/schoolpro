<?php

namespace admin\ScolariteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \admin\UserBundle\Types\TypeCodeProfil;
use \admin\UserBundle\Entity\Profil;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ClasseType extends AbstractType {

    public function __construct() {
        
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
        ->add('libelleClasse', TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Nom *', 'class' => 'form-control','ready'=>true)))
        ->add('descriptionClasse', TextareaType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => "Description *", 'class' => 'tel form-control ckeditor')))       
        ->add('filiere', EntityType::class, array(
                        'class' => 'adminScolariteBundle:Filiere',
                        'choice_label' => 'libelleFiliere',
                        'multiple' => false,
                        'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                        $qb = $repository->createQueryBuilder('m')
                        ->where('m.etatFiliere = ?0')        
                        ->setParameters(array(0 => \admin\UserBundle\Types\TypeEtat::ACTIF));
                        return $qb;
                        },
                        'required' => TRUE,
                        'attr' => array('required' => TRUE, 'class' => 'form-control libelle-class info-filiere')))  
        ->add('niveau', EntityType::class, array(
                        'class' => 'adminScolariteBundle:Niveau',
                        'choice_label' => 'libelleNiveau',
                        'multiple' => false,
                        'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                        $qb = $repository->createQueryBuilder('m')
                        ->where('m.etatNiveau = ?0')        
                        ->setParameters(array(0 => \admin\UserBundle\Types\TypeEtat::ACTIF));
                        return $qb;
                        },
                        'required' => TRUE,
                        'attr' => array('required' => TRUE, 'class' => 'form-control libelle-class info-niveau')))  
         ->add('indice', EntityType::class, array(
                        'class' => 'adminScolariteBundle:Indice',
                        'choice_label' => 'libelleIndice',
                        'multiple' => false,
                        'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                        $qb = $repository->createQueryBuilder('m')
                        ->where('m.etatIndice = ?0')        
                        ->setParameters(array(0 => \admin\UserBundle\Types\TypeEtat::ACTIF));
                        return $qb;
                        },
                        'required' => TRUE,
                        'attr' => array('required' => TRUE, 'class' => 'form-control libelle-class info-indice')))  
            ;
    }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'admin\ScolariteBundle\Entity\Classe'
        ));
    }
    /**
     * @return string
     */
    public function getName() {
        return 'admin_scolaritebundle_classe';
    }

}
