<?php

namespace StudentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', 'entity', array(
                        'class' => 'StudentBundle:Role',
                        'multiple' => true,
                        'property' => 'name',
                        'label' => 'Role : ',
                        )
                    )
            ->add('username')
            ->add('password', 'password')
            ->add('email')
            ->add('isActive')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'StudentBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sm_bundle_user';
    }
}
