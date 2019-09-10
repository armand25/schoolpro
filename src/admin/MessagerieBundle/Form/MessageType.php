<?php

namespace admin\MessagerieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MessageType extends AbstractType
{
    private $oldMessage;

    public function __construct($oldMessage)
    {
        $this->oldMessage = $oldMessage;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->oldMessage == null) {
            $builder
                    ->add('objet', 'text', array('attr' => array('required' => true, 'class' => 'rapidSearch'), 'required' => true))
                    ->add('contenuMessage', 'textarea', array('attr' => array('required' => true), 'required' => true));
        } else {
            $builder
                    ->add('objet', 'text', array('attr' => array('required' => true, 'class' => 'rapidSearch', 'readonly' => true), 'required' => true))
                    ->add('contenuMessage', 'textarea', array('attr' => array('required' => true, 'readonly' => true), 'required' => true));
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'admin\MessagerieBundle\Entity\Message',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_messageriebundle_messagec';
    }
}
