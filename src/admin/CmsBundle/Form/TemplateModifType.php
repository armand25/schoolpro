<?php

namespace admin\CmsBundle\Form;

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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TemplateModifType extends AbstractType {

    public function __construct() {
        
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
            ->add('titreTemplate', TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'titre*', 'class' => 'form-control')))
            ->add('descriptionTemplate', TextareaType::class, array('required' => FALSE, 'attr' => array('required' => FAlSE, 'placeholder' => "Description ", 'class' => 'tel form-control')))       
//            ->add('file',  FileType::class, array('required' => False, 'attr' => array('required' => False)))
     ;
    }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'admin\CmsBundle\Entity\Template'
        ));
    }
    /**
     * @return string
     */
    public function getName() {
        return 'admin_cmsbundle_template';
    }

}
