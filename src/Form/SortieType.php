<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,
            ['label'=>'Nom de la sortie'])
            ->add('dateHeureDebut', DateTimeType::class,[
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('duree', IntegerType::class,[
                'label'=>'Durée (j)'
            ])
            ->add('dateLimiteInscription',DateType::class,[
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('nbInscriptionMax', IntegerType::class,[
                'label'=>'Nombre de place'
            ])
            ->add('infoSortie', TextareaType::class, [
                'label'=>'Description et Infos'
            ])
            ->add('etatsortie', EntityType::class,[
                'choice_label' =>'libelle',
                'class'=>Etat::class,
                'placeholder'=>'choisir un etat'
            ])
            ->add('campus', EntityType::class,[
                'choice_label' =>'nom',
                'class'=>Campus::class,
                'placeholder'=>'choisir un campus'
            ])
                 ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
