<?php

namespace NS\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminType extends AbstractType
{
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'ns_core_admin';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array(
                'required' => true,
                'label'    => 'Адрес электронной почты',
            ))
            ->add('password', 'password', array(
                'required' => true,
                'label'    => 'Пароль',
            ))
        ;
    }


}