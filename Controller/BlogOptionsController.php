<?php

namespace Akyos\BlogBundle\Controller;

use Akyos\BlogBundle\Entity\BlogOptions;
use Akyos\BlogBundle\Form\BlogOptionsType;
use Akyos\BlogBundle\Repository\BlogOptionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/blog/options', name: 'blog_options')]
class BlogOptionsController extends AbstractController
{
    /**
     * @param BlogOptionsRepository $blogOptionsRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(path: '/', name: '', methods: ['GET', 'POST'])]
    public function index(BlogOptionsRepository $blogOptionsRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $blogOption = $blogOptionsRepository->findAll();
        $blogOption = $blogOption ? $blogOption[0] : new BlogOptions();
        $entities = [];
        $meta = $entityManager->getMetadataFactory()->getAllMetadata();
        foreach ($meta as $m) {
            $entities[] = $m->getName();
        }
        $form = $this->createForm(BlogOptionsType::class, $blogOption, ['entities' => $entities]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($blogOption);
            $entityManager->flush();

            return $this->redirectToRoute('blog_options');
        }
        return $this->render('@AkyosCms/cms_options/new.html.twig', [
            'bundle_name' => 'Blog options',
            'blog_option' => $blogOption,
            'form' => $form->createView(),
        ]);
    }
}
