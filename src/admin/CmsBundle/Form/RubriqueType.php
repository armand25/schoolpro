<?php

namespace admin\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class RubriqueType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('designationRubrique',TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Désignation de la rubrique *', 'class' => 'form-control')))
            ->add('rubrique', EntityType::class, array(
                'class' => 'adminCmsBundle:Rubrique',
                'choice_label' => 'designationRubrique',
                'multiple' => false,
                'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('r')
                           ;
                    return $qb;
                },
                        
                'required' => TRUE,
                'attr' => array('required' => TRUE, 'placeholder' => 'Rubrique parent *', 'class' => 'form-control')))
            ->add('descriptionRubrique',TextareaType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Description de la rubrique', 'class' => 'form-control ckeditor')))    
            ->add('classHtmlRubrique',TextType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => 'Classe HTML de la rubrique', 'class' => 'form-control')))
            ->add('iconeRubrique',TextType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => 'Icône bootstrap de la rubrique', 'class' => 'form-control')))
            ->add('medias',  CollectionType::class, array('entry_type' => 'admin\CmsBundle\Form\MediaType', 'allow_add' => true))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'admin\CmsBundle\Entity\Rubrique'
        ));
    }
    
 /**
     * @return string
     */
    public function getName()
    {
        return 'admin_cmsbundle_rubrique';
    }    
}
