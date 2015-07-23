<?php

namespace StudentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

// needed namespaces for 2.0 validation
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;

class SubjectType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text', array(
                        'required'=> FALSE,
                        'mapped' => false))
            ->add('time','text', array('required'=> FALSE))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'StudentBundle\Entity\Subject'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sm_bundle_subject';
    }
}
