<?php

namespace admin\EconomatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PlanComptableType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('compte', TextType::class, array('label' => 'Numéro de compte', 'attr' => array(
                        'placeholder' => "Saisir le numéro de compte *","class" => "form-control"
            )))
                ->add('libelle', TextType::class, array('label' => 'Libellé du compte','attr' => array(
                        'placeholder' => "Saisir le libellé du compte","class" => "form-control"
            )))
                ->add('type', ChoiceType::class, array(
                    'choices' => array('Mixte' => 'M', 'Débit' => 'D', 'Crédit' => 'C'),
                    
                    'required' => true,'attr' => array(
                    'placeholder' => "Selectionner la position *","class" => "form-control"
//                    'preferred_choices' => array('0')
                    //'preferred_choices' => array('M')
            )))

                 ->add('liea', ChoiceType::class, array(
                  'choices' => array(
                                     'A aucun ' => '0', 
				      'A une caisse' => '1', 
                                     'Aux comptes étudiants' => '2',
                                     'Aux comptes étudiants' =>'3' ,
				   ),
                     'required' => true,'attr' => array(
                    'placeholder' => "Selectionner la position *","class" => "form-control")
            ))
                
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'admin\EconomatBundle\Entity\PlanComptable'
        ));
    }

    public function getName() {
        return 'admin_economatbundle_plancomptabletype';
    }

}
