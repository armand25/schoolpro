<?php

namespace admin\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \admin\UserBundle\Entity\Profil;
use \admin\UserBundle\Types\TypeCodeProfil;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ModifierAbonneType extends AbstractType {

  

    public function __construct() {
        
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
                    'choices' => array(\admin\UserBundle\Types\TypeSexe::MASCULIN => 'Masculin',
                        \admin\UserBundle\Types\TypeSexe::FEMININ => 'Feminin',
                    ),
                    'preferred_choices' => array('Masculin'), 'attr' => array('class' => 'form-control')
                        )
                )
//                ->add('username', TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => "Nom d'utilisateur *", 'class' => 'form-control')))
                ->add('tel1', TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => "Téléphone *", 'class' => 'tel form-control')))
                ->add('tel2', TextType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => "Téléphone", 'class' => 'tel form-control')))
                ->add('email', EmailType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => "Email *" ,  'class' => 'form-control')))
                ->add('adresse', TextareaType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => "Adresse *",  'class' => 'form-control')))
                ->add('numCompte', TextType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => "Numéro compte *",  'class' => 'form-control')))
//                ->add('codePayeur', TextType::class, array('required' => TRUE, 'label' => 'Code payeur/emetteur', 'attr' => array('required' => TRUE, 'placeholder' => 'Code payeur/émetteur *')))
        ->add('profil', EntityType::class, array(
                'class' => 'adminUserBundle:Profil',
                'choice_label' => 'nom',
                'multiple' => false,
                'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('m')
                            ->where('m.etat = ?0')
                            ->andWhere('m.typeProfil =:tp')
                            ->setParameters(array(0 => \admin\UserBundle\Types\TypeEtat::ACTIF, 'tp' => \admin\UserBundle\Types\TypeProfil::PROFIL_ABONNE));
                    return $qb;
                }
                            ,
                            'required' => TRUE,
                            'attr' => array('required' => TRUE,  'class' => 'form-control')));
                            
                
//                        ->add('password', 'password', array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Mot de passe *')))
//                        ->add('cPassword', 'password', array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Confirmer le mot de passe *', 'label' => "Confirmation")))
                    }

                    /**
                     * @param OptionsResolverInterface $resolver
                     */
                    public function setDefaultOptions(OptionsResolver $resolver) {
                        $resolver->setDefaults(array(
                            'data_class' => 'admin\UserBundle\Entity\Abonne'
                        ));
                    }

                    /**
                     * @return string
                     */
                    public function getName() {
                        return 'admin_userbundle_modifierabonne';
                    }

                }
                