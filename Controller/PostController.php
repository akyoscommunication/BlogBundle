<?php

namespace Akyos\BlogBundle\Controller;

use Akyos\BlogBundle\Entity\Post;
use Akyos\BlogBundle\Form\Type\Post\NewPostType;
use Akyos\BlogBundle\Form\Type\Post\PostType;
use Akyos\BlogBundle\Repository\BlogOptionsRepository;
use Akyos\BlogBundle\Repository\PostRepository;
use Akyos\CmsBundle\Repository\SeoRepository;
use Akyos\CmsBundle\Service\CmsService;
use Akyos\CoreBundle\Form\Handler\CrudHandler;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Akyos\BuilderBundle\Entity\BuilderOptions;
use Akyos\BuilderBundle\AkyosBuilderBundle;

#[Route(path: '/admin/blog/article', name: 'blog_post_')]
#[IsGranted('liste-des-articles')]
class PostController extends AbstractController
{
    /**
     * @param PostRepository $postRepository
     * @param BlogOptionsRepository $blogOptionsRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param CrudHandler $crudHandler
     * @return Response
     */
    #[Route(path: '/', name: 'index', methods: ['GET', 'POST'])]
    public function index(PostRepository $postRepository, BlogOptionsRepository $blogOptionsRepository, PaginatorInterface $paginator, Request $request, CrudHandler $crudHandler): Response
    {
        $orderPostsByPosition = false;
        $blogOptions = $blogOptionsRepository->findAll();
        if ($blogOptions) {
            if (!$blogOptions[0]->getHasPosts()) {
                return $this->redirectToRoute('cms_index');
            }
            $orderPostsByPosition = $blogOptions[0]->getOrderPostsByPosition();
        }
        $query = $postRepository->createQueryBuilder('a');
        if ($request->query->get('search')) {
            $query->leftJoin('a.postCategories', 'postCategories')->andWhere('a.title LIKE :keyword OR a.position LIKE :keyword OR postCategories.title LIKE :keyword')->setParameter('keyword', '%' . $request->query->get('search') . '%');
        }
        if ($orderPostsByPosition) {
            $query->orderBy('a.position', 'ASC');
        } else {
            $query->orderBy('a.createdAt', 'DESC');
        }
        $els = $paginator->paginate($query->getQuery(), $request->query->getInt('page', 1), 12);
        $post = new Post();
        $post->setPublished(false);
        $post->setPosition($postRepository->count([]));
        $newPostForm = $this->createForm(NewPostType::class, $post);
        if ($crudHandler->new($newPostForm, $request)) {
            return $this->redirectToRoute('blog_post_edit', ['id' => $post->getId()]);
        }
        return $this->render('@AkyosCms/crud/index.html.twig', ['els' => $els, 'title' => 'Article', 'entity' => Post::class, 'view' => 'single', 'route' => 'blog_post', 'formModal' => $newPostForm->createView(), 'bundle' => 'CmsBundle', 'fields' => ['ID' => 'Id', 'Title' => 'Title', 'Catégorie(s)' => 'PostCategories', 'Position' => 'Position', 'En ligne ?' => 'Published', 'Publié le' => 'PublishedAt', 'Mis à jour le' => 'UpdatedAt',],]);
    }

    /**
     * @param PostRepository $postRepository
     * @param BlogOptionsRepository $blogOptionsRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(PostRepository $postRepository, BlogOptionsRepository $blogOptionsRepository, EntityManagerInterface $entityManager): Response
    {
        $blogOptions = $blogOptionsRepository->findAll();
        if ($blogOptions && !$blogOptions[0]->getHasPosts()) {
            return $this->redirectToRoute('cms_index');
        }
        $post = new Post();
        $post->setPublished(false);
        $post->setTitle("Nouvel article");
        $post->setPosition($postRepository->count([]));
        $entityManager->persist($post);
        $entityManager->flush();
        return $this->redirectToRoute('blog_post_edit', ['id' => $post->getId()]);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @param BlogOptionsRepository $blogOptionsRepository
     * @param CmsService $cmsService
     * @param ContainerInterface $container
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, BlogOptionsRepository $blogOptionsRepository, CmsService $cmsService, ContainerInterface $container, EntityManagerInterface $entityManager): Response
    {
        $entity = get_class($post);
        $blogOptions = $blogOptionsRepository->findAll();
        if ($blogOptions && !$blogOptions[0]->getHasPosts()) {
            return $this->redirectToRoute('cms_index');
        }
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        $classBuilder = AkyosBuilderBundle::class;
        $classBuilderOption = BuilderOptions::class;
        if ($cmsService->checkIfBundleEnable($classBuilder, $classBuilderOption, $entity) && !$form->isSubmitted()) {
            $container->get('render.builder')->initCloneComponents($entity, $post->getId());
        }
        if ($form->isSubmitted() && $form->isValid()) {
            if ($cmsService->checkIfBundleEnable($classBuilder, $classBuilderOption, $entity)) {
                $container->get('render.builder')->tempToProd($entity, $post->getId());
            }
            $entityManager->flush();

            return $this->redirect($request->getUri());
        }
        if ($form->isSubmitted() && !($form->isValid())) {
            throw $this->createNotFoundException("Formulaire invalide.");
        }
        return $this->render('@AkyosCms/crud/edit.html.twig', ['el' => $post, 'title' => 'Article', 'entity' => $entity, 'route' => 'blog_post', 'view' => 'single', 'form' => $form->createView(),]);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @param PostRepository $postRepository
     * @param BlogOptionsRepository $blogOptionsRepository
     * @param SeoRepository $seoRepository
     * @param CmsService $cmsService
     * @param ContainerInterface $container
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(path: '/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, Post $post, PostRepository $postRepository, BlogOptionsRepository $blogOptionsRepository, SeoRepository $seoRepository, CmsService $cmsService, ContainerInterface $container, EntityManagerInterface $entityManager): Response
    {
        $entity = get_class($post);
        $blogOptions = $blogOptionsRepository->findAll();
        if ($blogOptions && !$blogOptions[0]->getHasPosts()) {
            return $this->redirectToRoute('cms_index');
        }
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $classBuilder = AkyosBuilderBundle::class;
            $classBuilderOption = BuilderOptions::class;
            if ($cmsService->checkIfBundleEnable($classBuilder, $classBuilderOption, $entity)) {
                $container->get('render.builder')->onDeleteEntity($entity, $post->getId());
            }

            $seo = $seoRepository->findOneBy(['type' => $entity, 'typeId' => $post->getId()]);
            if ($seo) {
                $entityManager->remove($seo);
            }

            $entityManager->remove($post);
            $entityManager->flush();

            $position = 0;
            foreach ($postRepository->findBy([], ['position' => 'ASC']) as $el) {
                $el->setPosition($position);
                $position++;
            }
            $entityManager->flush();
        }
        return $this->redirectToRoute('blog_post_index');
    }
}
