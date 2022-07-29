<?php

namespace Akyos\BlogBundle\Controller;

use Akyos\BlogBundle\Entity\PostTag;
use Akyos\BlogBundle\Form\PostTagType;
use Akyos\BlogBundle\Repository\PostTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/blog/etiquettes", name="blog_post_tag_")
 * @IsGranted("etiquette-darticles")
 */
class PostTagController extends AbstractController
{
	/**
	 * @Route("/", name="index", methods={"GET"})
	 * @param PostTagRepository $postTagRepository
	 * @param PaginatorInterface $paginator
	 * @param Request $request
	 * @return Response
	 */
	public function index(PostTagRepository $postTagRepository, PaginatorInterface $paginator, Request $request): Response
	{
		$query = $postTagRepository->createQueryBuilder('a');
		if ($request->query->get('search')) {
			$query
				->andWhere('a.title LIKE :keyword OR a.content LIKE :keyword')
				->setParameter('keyword', '%' . $request->query->get('search') . '%');
		}
		$els = $paginator->paginate($query->getQuery(), $request->query->getInt('page', 1), 12);

		return $this->render('@AkyosCms/crud/index.html.twig', [
			'els' => $els,
			'title' => 'Étiquettes d\'article',
			'entity' => 'PostTag',
			'view' => 'tag',
			'route' => 'blog_post_tag',
			'fields' => [
				'ID' => 'Id',
				'Title' => 'Title',
				'Description' => 'Content',
			],
		]);
	}
	
	/**
	 * @Route("/new", name="new", methods={"GET","POST"})
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 */
	public function new(Request $request, EntityManagerInterface $entityManager): Response
	{
		$postTag = new PostTag();
		$form = $this->createForm(PostTagType::class, $postTag);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->persist($postTag);
			$entityManager->flush();

			return $this->redirectToRoute('blog_post_tag_index');
		}

		return $this->render('@AkyosCms/crud/new.html.twig', [
			'el' => $postTag,
			'title' => 'Étiquette d\'article',
			'entity' => 'PostTag',
			'route' => 'blog_post_tag',
			'form' => $form->createView(),
		]);
	}
	
	/**
	 * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
	 * @param Request $request
	 * @param PostTag $postTag
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 */
	public function edit(Request $request, PostTag $postTag, EntityManagerInterface $entityManager): Response
	{
		$form = $this->createForm(PostTagType::class, $postTag);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->flush();

			return $this->redirectToRoute('blog_post_tag_index');
		}

		return $this->render('@AkyosCms/crud/edit.html.twig', [
			'el' => $postTag,
			'title' => 'Étiquette d\'article',
			'entity' => 'PostTag',
			'route' => 'blog_post_tag',
			'view' => 'archive',
			'form' => $form->createView(),
		]);
	}

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param PostTag $postTag
     * @return Response
     */
	public function delete(Request $request, PostTag $postTag, EntityManagerInterface $entityManager): Response
	{
		if ($this->isCsrfTokenValid('delete' . $postTag->getId(), $request->request->get('_token'))) {
			$entityManager->remove($postTag);
			$entityManager->flush();
		}
		return $this->redirectToRoute('blog_post_tag_index');
	}
}
