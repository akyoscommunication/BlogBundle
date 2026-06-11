<?php

namespace Akyos\BlogBundle\Service;

use Akyos\CmsBundle\Entity\AdminAccess;
use Akyos\CmsBundle\Repository\AdminAccessRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class ExtendAdminAccess
{
    private readonly EntityManagerInterface $entityManager;

    public function __construct(private readonly AdminAccessRepository $adminAccessRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setDefaults(): Response
    {
        if (!$this->adminAccessRepository->findOneBy(['name' => "Liste des articles"])) {
            $adminAccess = new AdminAccess();
            $adminAccess->setName('Liste des articles')->setRoles([])->setIsLocked(true);
            $this->entityManager->persist($adminAccess);
            $this->entityManager->flush();
        }

        if (!$this->adminAccessRepository->findOneBy(['name' => "Catégories d'articles"])) {
            $adminAccess = new AdminAccess();
            $adminAccess->setName("Catégories d'articles")->setRoles([])->setIsLocked(true);
            $this->entityManager->persist($adminAccess);
            $this->entityManager->flush();
        }

        if (!$this->adminAccessRepository->findOneBy(['name' => "Etiquette d'articles"])) {
            $adminAccess = new AdminAccess();
            $adminAccess->setName("Etiquette d'articles")->setRoles([])->setIsLocked(true);
            $this->entityManager->persist($adminAccess);
            $this->entityManager->flush();
        }

        return new Response('true');
    }
}