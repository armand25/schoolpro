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
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EtablissementType extends AbstractType {

    public function __construct() {
        
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
        ->add('libelleEtablissement', TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Nom *', 'class' => 'form-control')))
        ->add('contactEtablissement', TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Contact *', 'class' => 'form-control')))
        ->add('adresseEtablissement', TextareaType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => "Adresse *", 'class' => 'tel form-control')))       
        ->add('deviseEtablissement', TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => "Devise *", 'class' => 'form-control')))
        ->add('file', FileType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => "Devise *", 'class' => 'form-control')))
        ;
    }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'admin\ScolariteBundle\Entity\Etablissement'
        ));
    }
    /**
     * @return string
     */
    public function getName() {
        return 'admin_scolaritebundle_etablissement';
    }

}
