<?php

namespace Akyos\BlogBundle\Form\Type\Post;

use Akyos\BlogBundle\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', null, ['label' => 'Titre', 'help' => 'Insérez votre titre ici',])->add('publishedAt', DateType::class, ['widget' => 'single_text', 'label' => 'Date de publication']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Post::class,]);
    }
}
