<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColorJeansType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('couleur', ChoiceType::class, [
            'choices' => [
                'couleur' => [
                    'bleu_fonce' => 'Bleu_fonce',
                    'bleu_clair' => 'Bleu_clair',
                    'noir' => 'Noir',
                    'gris' => 'Gris',
                    'blanc' => 'Blanc',

                ],

                
            ],        


        ]) 
        ->add('valider', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
