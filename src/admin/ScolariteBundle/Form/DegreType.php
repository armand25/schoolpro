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

class DegreType extends AbstractType {

    public function __construct() {
        
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
        ->add('libelleDegre', TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Nom *', 'class' => 'form-control')))
        ->add('descriptionDegre', TextareaType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => "Description *", 'class' => 'tel form-control')))       
         ;
    }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'admin\ScolariteBundle\Entity\Degre'
        ));
    }
    /**
     * @return string
     */
    public function getName() {
        return 'admin_scolaritebundle_degre';
    }

}
