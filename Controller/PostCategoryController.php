<?php

namespace Akyos\BlogBundle\Controller;

use Akyos\BlogBundle\Entity\PostCategory;
use Akyos\BlogBundle\Form\PostCategoryType;
use Akyos\BlogBundle\Repository\PostCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/blog/categories', name: 'blog_post_category_')]
#[IsGranted('categories-darticles')]
class PostCategoryController extends AbstractController
{
    /**
     * @param PostCategoryRepository $postCategoryRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(PostCategoryRepository $postCategoryRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $postCategoryRepository->createQueryBuilder('a');
        if ($request->query->get('search')) {
            $query->andWhere('a.title LIKE :keyword OR a.content LIKE :keyword')->setParameter('keyword', '%' . $request->query->get('search') . '%');
        }
        $els = $paginator->paginate($query->getQuery(), $request->query->getInt('page', 1), 12);
        return $this->render('@AkyosCms/crud/index.html.twig', ['els' => $els, 'title' => 'Catégorie d\'article', 'entity' => 'PostCategory', 'view' => 'taxonomy', 'route' => 'blog_post_category', 'fields' => ['ID' => 'Id', 'Title' => 'Title', 'Description' => 'Content',],]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $postCategory = new PostCategory();
        $form = $this->createForm(PostCategoryType::class, $postCategory);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($postCategory);
            $entityManager->flush();

            return $this->redirectToRoute('blog_post_category_index');
        }
        return $this->render('@AkyosCms/crud/new.html.twig', ['el' => $postCategory, 'title' => 'Catégorie d\'article', 'entity' => 'PostCategory', 'route' => 'blog_post_category', 'form' => $form->createView(),]);
    }

    /**
     * @param Request $request
     * @param PostCategory $postCategory
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PostCategory $postCategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostCategoryType::class, $postCategory);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('blog_post_category_index');
        }
        return $this->render('@AkyosCms/crud/edit.html.twig', ['el' => $postCategory, 'title' => 'Catégorie d\'article', 'entity' => 'PostCategory', 'route' => 'blog_post_category', 'view' => 'archive', 'form' => $form->createView(),]);
    }

    /**
     * @param Request $request
     * @param PostCategory $postCategory
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(path: '/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, PostCategory $postCategory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $postCategory->getId(), $request->request->get('_token'))) {
            $entityManager->remove($postCategory);
            $entityManager->flush();
        }
        return $this->redirectToRoute('blog_post_category_index');
    }
}
