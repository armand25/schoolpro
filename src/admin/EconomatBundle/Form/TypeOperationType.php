<?php

namespace admin\EconomatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TypeOperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codeOperation',TextType::class,
                    array('label'=>'Code Type Operation','attr'=>array(
                        'placeholder'=>"Code de l'opération*",'class'=>"form-control"
                    )))
            ->add('libTypeOperation',TextType::class, array('label'=>'Libellé Type Operation *',
                                                   'attr'=>array('maxlength'=>"20", 'placeholder'=>"Libellé type opération *",'class'=>"form-control")
                ))      
                 
            ->add('nbreLigne', ChoiceType::class, array(
                  'choices' => array(
				    'Deux (2) Lignes Comptables'  => '2', 
                                    'Trois (3) Lignes Comptables'  =>  '3',
				    'Quatre (4) Lignes Comptables'  =>  '4', 
                                    'Cinq (5) Lignes Comptables'  =>  '5',
                                    'Six (6) Lignes Comptables'  =>  '6',
                                    'Sept (7) Lignes Comptables'  =>  '7', 
                                    'Huit (8) Lignes Comptables'  =>  '8',),
                'attr'=>array('maxlength'=>"20", 'placeholder'=>"Libellé type opération *",'class'=>"form-control")
                  
                ))
            ->add('mouvFonds', ChoiceType::class, array(
                  'choices' => array(
				     'Inscription'  => '0' , 
                                     'Paiement des salaires'  => '1',
				     'Retrait'  => '2', 
                                     'Ecarts de Caisse'  => '3' ,

                                     ),
             
                 'attr'=>array('maxlength'=>"20", 'placeholder'=>"Libellé type opération *",'class'=>"form-control") 
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'admin\StockBundle\Entity\TypeOperation'
        ));
    }

    public function getName()
    {
        return 'admin_stockbundle_agencetype';
    }
}
