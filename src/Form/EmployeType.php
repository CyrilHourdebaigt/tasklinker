<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true,
                'label' => 'Nom',
                'attr' => ['id' => 'employe_nom'],
            ])
            ->add('prenom', TextType::class, [
                'required' => true,
                'label' => 'Prenom',
                'attr' => ['id' => 'employe_prenom'],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'attr' => ['id' => 'employe_email'],
            ])
            ->add('dateEntree', DateType::class, [
                'required' => true,
                'label' => "Date d'entrée",
                'widget' => 'single_text',
                'attr' => ['id' => 'employe_dateArrivee'],
            ])
            ->add('statut', TextType::class, [
                'required' => true,
                'label' => 'Statut',
                'attr' => ['id' => 'employe_statut'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
