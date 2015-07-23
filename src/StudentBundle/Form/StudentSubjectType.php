<?php

namespace StudentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StudentSubjectType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', 'entity', array(
                        'class' => 'StudentBundle:Subject',
                        'multiple' => true,
                        'property' => 'name',
                        'label' => 'Role : ',
                        )
                    )
            ->add('name')
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
        return 'sm_bundle_studentsubject';
    }
}
