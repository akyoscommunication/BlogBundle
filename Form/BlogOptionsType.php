<?php

namespace Akyos\BlogBundle\Form;

use Akyos\BlogBundle\Entity\BlogOptions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogOptionsType extends AbstractType
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
        ;
	}
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => BlogOptions::class,
			'entities' => []
		]);
	}
}
