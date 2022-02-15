<?php

namespace Akyos\BlogBundle\Form;

use Akyos\BlogBundle\Entity\CoreOptions;
use Akyos\BlogBundle\Entity\Page;
use Akyos\BlogBundle\Repository\PageRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoreOptionsType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('hasPosts', CheckboxType::class, [
				'label' => 'Activation du blog',
				'required' => false
			])
			->add('hasPostDocuments', CheckboxType::class, [
				'label' => 'Activation des documents d\'articles',
				'required' => false
			])
			->add('orderPostsByPosition', CheckboxType::class, [
				'label' => 'Trier les articles par position ? (dans l\'admin, par défaut triés par date de création)',
				'required' => false
			])
			->add('homepage', EntityType::class, [
				'label' => "Page d'accueil",
				'required' => false,
				'class' => Page::class,
				'query_builder' => function (PageRepository $er) {
					return $er->createQueryBuilder('p')
						->orderBy('p.position', 'ASC');
				},
				'choice_label' => 'title',
				'placeholder' => "Choisissez une page"
			])
			->add('hasArchiveEntities', ChoiceType::class, [
				'label' => 'Activer la page archive sur les entités :',
				'choices' => $options['entities'],
				'choice_label' => function ($choice, $key, $value) {
					return $value;
				},
				'multiple' => true,
				'expanded' => true
			])
			->add('hasSingleEntities', ChoiceType::class, [
				'label' => 'Activer les pages single sur les entités :',
				'choices' => $options['entities'],
				'choice_label' => function ($choice, $key, $value) {
					return $value;
				},
				'multiple' => true,
				'expanded' => true
			])
			->add('hasSeoEntities', ChoiceType::class, [
				'label' => 'Activer le SEO sur les entités :',
				'choices' => $options['entities'],
				'choice_label' => function ($choice, $key, $value) {
					return $value;
				},
				'multiple' => true,
				'expanded' => true
			]);
	}
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => CoreOptions::class,
			'entities' => []
		]);
	}
}
