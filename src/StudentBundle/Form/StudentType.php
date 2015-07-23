<?php

namespace StudentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\File\File;
class StudentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder,  array $options)
    {
        $builder
            ->add('school', 'entity', array(
                    'class' => 'StudentBundle:School',
                    'property' => 'name',
                    'label' => 'School : ',
                    )
                )
            ->add('name', 'text' , array('required'=> true))
            ->add('image', 'file', array('required'=> true,
                        'data_class' => null,
                ))
            ->add('age', 'text' , array('required'=> true))
            ->add('render', 'choice', array('choices'=> array('Male' => 'Male', 'Female' => 'Female')))
            ->add('brochure', 'file', array('label' => 'Brochure (ODT file)',
                        'data_class' => null,
                        'required'=> FALSE,

                        ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'StudentBundle\Entity\Student'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'student';
    }
}
