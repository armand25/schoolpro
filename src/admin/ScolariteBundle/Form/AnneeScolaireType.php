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

class AnneeScolaireType extends AbstractType {

    public function __construct() {
        
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
        ->add('libelleAnneeScolaire', TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Libellé *', 'class' => 'form-control inputDate')))
        ->add('dateRentreAnneeScolaire', TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'date Rentre *', 'class' => 'form-control inputDate')))
        ->add('dateVacanceAnneeScolaire', TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => "Date vancance *", 'class' => ' form-control inputDate')))       
        ;
    }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'admin\ScolariteBundle\Entity\AnneeScolaire'
        ));
    }
    /**
     * @return string
     */
    public function getName() {
        return 'admin_scolaritebundle_anneescolaire';
    }

}
