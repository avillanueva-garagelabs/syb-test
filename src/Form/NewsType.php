<?php

namespace App\Form;

use App\Entity\News;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Título',
            ])
            ->add('publicationDate', DateTimeType::class, [
                'label' => 'Fecha de Publicación',
                'widget' => 'single_text',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
            ])
            /* ->add('mainPhoto', FileType::class, [
                'label' => 'Foto Principal',
                'mapped' => false, // No está mapeado directamente a la entidad
                'required' => false,
            ]) */
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Categoría',
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'Habilitada',
                'required' => false,
            ])
            ->add('file', FileType::class, [
                'label' => 'Foto principal',
                /* 'is_image' => true,
                'file_url_options' => ['signed' => true], */
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
