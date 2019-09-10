<?php

namespace admin\ScolariteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use \admin\UserBundle\Types\TypeCodeProfil;
use \admin\UserBundle\Entity\Profil;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use \Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use admin\ScolariteBundle\Form\GenderType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EleveType extends AbstractType {

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
        ->add('matricule', TextType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => 'Matricule', 'class' => 'form-control')))
        ->add('sexe', GenderType::class,array('placeholder' =>'Sexe','attr' => array('required' => TRUE, 'class' => 'form-control'))
        )
//                ->add('username', TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => "Nom d'utilisateur *", 'class' => 'form-control')))
        ->add('tel1', TextType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => "Téléphone *", 'class' => 'tel form-control')))
//      ->add('imagepers', CollectionType::class, array('entry_type' => 'admin\UserBundle\Form\ImagePersonneType', 'allow_add' => true))
       
        ->add('email', EmailType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => "Email *", 'class' => 'form-control')))
        ->add('adresse', TextareaType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => "Adresse *", 'class' => 'form-control')))
        ->add('etablissementOrigine', TextType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => "Etablissement d'origine", 'class' => 'form-control')))
        ->add('numeroPieceNaissance', TextType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => "Numéro de piece de naissance", 'class' => 'form-control')))
        ->add('numeroPieceNationnalite', TextType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => "Numéro de piece de nationnalité", 'class' => 'form-control')))
        ->add('lieuNaissance', TextType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => "Lieu de naissance", 'class' => 'form-control')))
        ->add('bp', TextType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => "Boite Postale", 'class' => 'form-control')))
        ->add('dateNaissance', TextType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => "Date de naissance", 'class' => 'form-control inputDate')))
        ->add('nationalite', CountryType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => "Nationnailité *", 'class' => 'form-control')))
//        ->add('numCompte', TextType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => "Numero Compte *", 'class' => 'form-control')))
//        ->add('profil', EntityType::class, array(
//        'class' => 'adminUserBundle:Profil',
//        'choice_label' => 'nom',
//        'multiple' => false,
//        'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
//        $qb = $repository->createQueryBuilder('m')
//        ->where('m.etat = ?0')
//        ->andWhere('m.typeProfil =:tp')
//        ->setParameters(array(0 => \admin\UserBundle\Types\TypeEtat::ACTIF, 'tp' => \admin\UserBundle\Types\TypeProfil::PROFIL_ABONNE));
//        return $qb;
//        },
//        'required' => TRUE,
//        'attr' => array('required' => TRUE, 'class' => 'form-control')))
        ->add('password', HiddenType::Class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Mot de passe *', 'class' => 'form-control')))
        ->add('cPassword', HiddenType::Class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Confirmer le mot de passe *', 'label' => "Confirmation", 'class' => 'form-control')))
        ->add('file',  FileType::class, array('required' => FALSE, 'attr' => array('required' => FALSE)))
       
            ;
    }
     /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'admin\ScolariteBundle\Entity\Eleve'
        ));
    }
    
    /**
     * @return string
     */
    public function getName() {
        return 'admin_scolaritebundle_eleve';
    }

}
