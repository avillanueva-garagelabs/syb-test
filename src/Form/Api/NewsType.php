<?php

namespace App\Form\Api;

use App\Entity\News;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class NewsType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('title', TextType::class, [
        'required' => true,
      ])
      ->add('publicationDate', DateTimeType::class, [
        'required' => false,
        'widget' => 'single_text', 
        'input' => 'datetime',
        'empty_data' => new DateTime(),
      ])
      ->add('description', TextareaType::class, [
        'required' => true,
      ])
      ->add('file', FileType::class, [
        'required' => false,
        'mapped' => false, 
        'constraints' => [
          new File([
            'maxSize' => '5M', 
            'mimeTypes' => [ 
              'image/jpeg',
              'image/png',
              'image/gif',
            ],
            'mimeTypesMessage' => 'Por favor, sube una imagen válida (JPEG, PNG o GIF).',
          ]),
        ],
      ])
      ->add('enabled', CheckboxType::class, [
        'required' => false,
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => News::class,
      'csrf_protection' => false, 
    ]);
  }
}
