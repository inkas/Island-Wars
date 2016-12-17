<?php

namespace MyGameBundle\Form;

use MyGameBundle\Entity\IslandTroop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TroopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', IntegerType::class, [
                'label' => false,
                'attr' => ['class' => 'input-sm'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Train', 'attr' => ['class' => 'btn-block btn-sm btn-info']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IslandTroop::class
        ]);
    }

}
