<?php

namespace admin\ScolariteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \admin\UserBundle\Entity\Profil;
use \admin\UserBundle\Types\TypeCodeProfil;

use Symfony\Component\Form\Extension\Core\Type\FileType;


class ModifierElevePhotoType extends AbstractType {

  

    public function __construct() {
        
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

   $builder
       ->add('file',  FileType::class, array('required' => FALSE, 'attr' => array('required' => FALSE)));
    }
                    /**
                     * @param OptionsResolverInterface $resolver
                     */
                    public function setDefaultOptions(OptionsResolver $resolver) {
                        $resolver->setDefaults(array(
                            'data_class' => 'admin\ScolariteBundle\Entity\Eleve'
                        ));
                    }

                    /**
                     * @return string
                     */
                    public function getName() {
                        return 'admin_userbundle_modifierabonnephoto';
                    }

                }
                