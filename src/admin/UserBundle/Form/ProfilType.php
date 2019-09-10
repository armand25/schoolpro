<?php

namespace admin\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\Extension\Core\Type\TextareaType;
use \Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProfilType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nom', TextType::class, array('required' => TRUE ,'attr' => array('required' => TRUE , 'placeholder' => 'Nom', 'class'=> 'form-control')))
                ->add('description', TextareaType::class, array('required' => TRUE ,'attr' => array('required' => TRUE, 'class' => 'form-control')))
                ->add('typeProfil', ChoiceType::class, array(
                    'choices' => array(\admin\UserBundle\Types\TypeProfil::PROFIL_UTILISATEUR => 'ADMINISTRATION',
                    \admin\UserBundle\Types\TypeProfil::PROFIL_ABONNE => 'UTILISATEUR',
                    ),
                    'preferred_choices' => array('ADMINISTRATION'), 'attr' => array('class' => 'form-control m-bot15' )
                        )
                )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'admin\UserBundle\Entity\Profil'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'admin_adminbundle_typeprofil';
    }

}
