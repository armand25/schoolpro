<?php

namespace admin\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class ArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Titre *', 'class' => 'form-control')))
             ->add('typeArticle', ChoiceType::class, array(
                    'choices' => array( 'Tous le monde'=>0
                    ),
                    'preferred_choices' => array('Masculin'), 'attr' => array('class' => 'form-control m-bot15')
                        )
                )
            ->add('rubrique', EntityType::class, array(
                'class' => 'adminCmsBundle:Rubrique',
                'choice_label' => 'designationRubrique',
                'multiple' => false,
                'query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                $qb = $repository->createQueryBuilder('r')
                           ->where('r.actifRubrique =:actifRubrique')
                           ->andWhere('r.typeRubrique =:typeRubrique')
                           ->setParameters(array('actifRubrique'=>1,'typeRubrique'=>0));
                    return $qb;
                },
                       
                        
                'required' => TRUE,
                'attr' => array('required' => TRUE, 'placeholder' => 'Rubrique concernée *', 'class' => 'form-control')))
            ->add('contenuArticle',TextareaType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Description de la rubrique', 'class' => 'form-control ckeditor')))    
            ->add('resumeArticle',TextareaType::class, array('required' => TRUE, 'attr' => array('required' => TRUE, 'placeholder' => 'Résumé de la rubrique', 'class' => 'form-control ')))    
            ->add('classHtmlArticle',TextType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => 'Classe HTML de cet article', 'class' => 'form-control')))
           // ->add('medias',  CollectionType::class, array('entry_type' => 'admin\CmsBundle\Form\MediaType', 'allow_add' => true))
			->add('file', FileType::class, array('required' => FALSE, 'attr' => array('required' => FALSE, 'placeholder' => "Devise *", 'class' => 'form-control')))
		;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'admin\CmsBundle\Entity\Article'
        ));
    }
}
