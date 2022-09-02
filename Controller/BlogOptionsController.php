<?php

namespace Akyos\BlogBundle\Controller\Back;

use Akyos\BlogBundle\Entity\BlogOptions;
use Akyos\BlogBundle\Form\BlogOptionsType;
use Akyos\BlogBundle\Repository\BlogOptionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        if (!$blogOption) {
            $blogOption = new BlogOptions();
        } else {
            $blogOption = $blogOption[0];
        }
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
        return $this->render('@AkyosBlog/blog_options/new.html.twig', ['blog_option' => $blogOption, 'form' => $form->createView(),]);
    }
}
