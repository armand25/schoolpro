<?php

namespace admin\ScolariteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \admin\UserBundle\Types\TypeCodeProfil;
use \admin\UserBundle\Entity\Profil;
use \admin\UserBundle\Types\TypeEtat;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EpreuveType extends AbstractType {

    public function __construct() {
        
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
        ->add('libelleEpreuve', TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Nom *', 'class' => 'form-control')))
        ->add('descriptionEpreuve', TextareaType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => "Description *", 'class' => ' form-control ckeditor'))) 
        ->add('typeexamen', EntityType::class, array(
                'class' => 'adminScolariteBundle:TypeExamen',
                'choice_label' => 'libelleTypeExamen',
                'multiple' => false,
                'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('m');
                    return $qb;
                },
                        
                'required' => TRUE,
                'attr' => array('required' => TRUE, 'class' => 'form-control')))        
        ->add('exercices', CollectionType::class, array(                    
                    'entry_type' => 'admin\ScolariteBundle\Form\ExerciceType',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,

                    ))
            ;
        
    }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'admin\ScolariteBundle\Entity\Epreuve'
        ));
    }
    /**
     * @return string
     */
    public function getName() {
        return 'admin_scolaritebundle_epreuve';
    }

}
