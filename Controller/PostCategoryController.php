<?php

namespace Akyos\BlogBundle\Controller;

use Akyos\BlogBundle\Entity\PostCategory;
use Akyos\BlogBundle\Form\PostCategoryType;
use Akyos\BlogBundle\Repository\PostCategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/blog/categories", name="blog_post_category_")
 * @IsGranted("categories-darticles")
 */
class PostCategoryController extends AbstractController
{
	/**
	 * @Route("/", name="index", methods={"GET"})
	 * @param PostCategoryRepository $postCategoryRepository
	 * @param PaginatorInterface $paginator
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(PostCategoryRepository $postCategoryRepository, PaginatorInterface $paginator, Request $request): Response
	{
		$query = $postCategoryRepository->createQueryBuilder('a');
		if ($request->query->get('search')) {
			$query
				->andWhere('a.title LIKE :keyword OR a.content LIKE :keyword')
				->setParameter('keyword', '%' . $request->query->get('search') . '%');
		}
		$els = $paginator->paginate($query->getQuery(), $request->query->getInt('page', 1), 12);

		return $this->render('@AkyosCore/crud/index.html.twig', [
			'els' => $els,
			'title' => 'Catégorie d\'article',
			'entity' => 'PostCategory',
			'view' => 'taxonomy',
			'route' => 'blog_post_category',
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
	 *
	 * @return Response
	 */
	public function new(Request $request): Response
	{
		$postCategory = new PostCategory();
		$form = $this->createForm(PostCategoryType::class, $postCategory);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($postCategory);
			$entityManager->flush();

			return $this->redirectToRoute('blog_post_category_index');
		}

		return $this->render('@AkyosCore/crud/new.html.twig', [
			'el' => $postCategory,
			'title' => 'Catégorie d\'article',
			'entity' => 'PostCategory',
			'route' => 'blog_post_category',
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
	 * @param Request $request
	 * @param PostCategory $postCategory
	 *
	 * @return Response
	 */
	public function edit(Request $request, PostCategory $postCategory): Response
	{
		$form = $this->createForm(PostCategoryType::class, $postCategory);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDoctrine()->getManager()->flush();

			return $this->redirectToRoute('blog_post_category_index');
		}

		return $this->render('@AkyosCore/crud/edit.html.twig', [
			'el' => $postCategory,
			'title' => 'Catégorie d\'article',
			'entity' => 'PostCategory',
			'route' => 'blog_post_category',
			'view' => 'archive',
			'form' => $form->createView(),
		]);
	}

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param PostCategory $postCategory
     * @return Response
     */
	public function delete(Request $request, PostCategory $postCategory): Response
	{
		if ($this->isCsrfTokenValid('delete' . $postCategory->getId(), $request->request->get('_token'))) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($postCategory);
			$entityManager->flush();
		}
		return $this->redirectToRoute('blog_post_category_index');
	}
}
