<?php

namespace NS\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DatabaseType extends AbstractType
{
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'ns_core_database';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('database_user', 'text', array(
                'required' => false,
                'label'    => 'Имя пользователя',
            ))
            ->add('database_password', 'text', array(
                'required' => false,
                'label'    => 'Пароль',
            ))
            ->add('database_name', 'text', array(
                'required' => false,
                'label'    => 'Имя базы данных',
            ))
            ->add('database_host', 'text', array(
                'required' => false,
                'label'    => 'Хост',
            ))
            ->add('database_port', 'text', array(
                'required' => false,
                'label'    => 'Порт',
            ))
        ;
    }


}