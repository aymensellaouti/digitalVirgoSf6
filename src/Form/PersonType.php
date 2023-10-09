<?php

namespace App\Form;

use App\Entity\Hobby;
use App\Entity\Job;
use App\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('age')
            ->add('cin')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('job', EntityType::class, [
                'class' => Job::class,
                'choice_label' => 'designation',
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('hobbies',EntityType::class, [
                'class' => Hobby::class,
                'choice_label' => 'description',
                'expanded' => false,
                'multiple' => true,
                'attr' => [
                    'class' => 'select2'
            ]])
            ->add('image', FileType::class, [
                'label' => 'Votre image de profil (Des fichiers images uniquement)',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Person details
                'required' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k'
                    ])
                ],
            ])
            ->add('edit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
