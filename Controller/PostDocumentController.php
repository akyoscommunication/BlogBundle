<?php

namespace Akyos\BlogBundle\Controller;

use Akyos\BlogBundle\Entity\Post;
use Akyos\BlogBundle\Entity\PostDocument;
use Akyos\BlogBundle\Form\Type\Post\PostDocumentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/blog/documents-articles", name="blog_post_document_")
 */
class PostDocumentController extends AbstractController
{
	/**
	 * @Route("/new/{id}", name="new", methods={"GET","POST"})
	 * @param Post $post
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 */
	public function new(Post $post, Request $request, EntityManagerInterface $entityManager): Response
	{
		$postDocument = new PostDocument();
		$form = $this->createForm(PostDocumentType::class, $postDocument);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$postDocument->setPost($post);
			$entityManager->persist($postDocument);
			$entityManager->flush();

			return $this->redirectToRoute('blog_post_edit', ["id" => $post->getId()]);
		}

		return $this->render('@AkyosCms/post_document/new.html.twig', [
			'parameters' => [
				'id' => $post->getId(),
				'tab' => 'postdoc',
			],
			'route' => 'blog_post_edit',
			'el' => $postDocument,
			'title' => 'Document d\'article',
			'entity' => 'PostDocument',
			'form' => $form->createView(),
		]);
	}
	
	/**
	 * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
	 * @param Request $request
	 * @param PostDocument $postDocument
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 */
	public function edit(Request $request, PostDocument $postDocument, EntityManagerInterface $entityManager): Response
	{
        /** @var Post $post */
        $post = $postDocument->getPost();
		$form = $this->createForm(PostDocumentType::class, $postDocument);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->flush();
			return $this->redirectToRoute('blog_post_edit', ["id" => $post->getId()]);
		}

		return $this->render('@AkyosCms/post_document/edit.html.twig', [
			'parameters' => [
				'id' => $post->getId(),
				'tab' => 'postdoc',
			],
			'el' => $postDocument,
			'title' => 'Document d\'article',
			'entity' => 'PostDocument',
			'route' => 'blog_post_document',
			'view' => 'archive',
			'form' => $form->createView(),
		]);
	}
	
	/**
	 * @Route("/{id}", name="delete", methods={"DELETE"})
	 * @param Request $request
	 * @param PostDocument $postDocument
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 */
	public function delete(Request $request, PostDocument $postDocument, EntityManagerInterface $entityManager): Response
	{
        /** @var Post $post */
        $post = $postDocument->getPost();

		if ($this->isCsrfTokenValid('delete' . $postDocument->getId(), $request->request->get('_token'))) {
			$entityManager->remove($postDocument);
			$entityManager->flush();
		}

		return $this->redirectToRoute('blog_post_index', [
			'id' => $post->getId()
		]);
	}
}
