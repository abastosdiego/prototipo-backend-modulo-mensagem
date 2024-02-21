<?php

namespace App\Form;

use App\Entity\Mensagem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MensagemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('assunto')
            ->add('mensagem')
            ->add('data_hora')
            ->add('data_entrada')
            ->add('prazo')
            ->add('autorizado')
            ->add('data_autorizacao')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mensagem::class,
        ]);
    }
}
