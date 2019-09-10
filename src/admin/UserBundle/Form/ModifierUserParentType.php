<?php

namespace admin\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \admin\UserBundle\Types\TypeEtat;
use \admin\UserBundle\Types\TypeProfil;
use \admin\UserBundle\Types\TypeCodeProfil;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ModifierUserParentType extends AbstractType {

    private $maxLengthTel = 8;
    private $idProfil = 0;

    /**
     * 
     * @var string  $userBundle
     * Nom du Bundle
     */
    private $userBundle = 'adminUserBundle:';

    public function __construct($maxLength=8, $idProfil = 0) {
        $this->maxLengthTel = $maxLength;
        $this->idProfil = $idProfil;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('nom', TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Nom *', 'class' => 'form-control')))
                ->add('prenoms', TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Prénoms *', 'class' => 'form-control')))
                ->add('sexe', ChoiceType::class, array(
                    'choices' => array(  'Masculin'=>\admin\UserBundle\Types\TypeSexe::MASCULIN,
                          'Feminin'=>\admin\UserBundle\Types\TypeSexe::FEMININ,
                    ),
                    'preferred_choices' => array('Masculin'), 'attr' => array('class' => 'form-control')
                        )
                )
//                ->add('username', TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => "Nom d'utilisateur *", 'class' => 'form-control')))
                ->add('tel1', TextType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => "Téléphone ", 'maxlength' => $this->maxLengthTel, 'class' => 'form-control numeric')))
                ->add('tel2', TextType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => "Téléphone", 'maxlength' => $this->maxLengthTel, 'class' => 'form-control numeric')))
                ->add('email', EmailType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => "Email *", 'class' => 'form-control')))
                ->add('adresse', TextareaType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => "Adresse *", 'class' => 'form-control')));


//                        ->add('password', 'password', array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Mot de passe *')))
//                        ->add('cPassword', 'password', array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Confirmer le mot de passe *', 'label' => "Confirmation")))
                    }

                    /**
                     * @param OptionsResolverInterface $resolver
                     */
                    public function setDefaultOptions(OptionsResolver $resolver) {
                        $resolver->setDefaults(array(
                            'data_class' => 'admin\UserBundle\Entity\Utilisateur'
                        ));
                    }

                    /**
                     * @return string
                     */
                    public function getName() {
                        return 'admin_userbundle_utilisateur';
                    }

                }
                