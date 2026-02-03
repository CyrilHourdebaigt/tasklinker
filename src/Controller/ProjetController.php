<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Repository\ProjetRepository;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjetController extends AbstractController
{
    #[Route('/', name: 'projet_index')]
    public function index(ProjetRepository $projetRepository): Response
    {
        return $this->render('projet/index.html.twig', [
            'projets' => $projetRepository->findAll(),
        ]);
    }


    #[Route('/projets/{id}', name: 'projet_show', requirements: ['id' => '\d+'])]
    public function show(Projet $projet, TacheRepository $tacheRepository): Response
    {
        $tachesToDo = $tacheRepository->findBy(['projet' => $projet, 'statut' => 'To Do']);
        $tachesDoing = $tacheRepository->findBy(['projet' => $projet, 'statut' => 'Doing']);
        $tachesDone = $tacheRepository->findBy(['projet' => $projet, 'statut' => 'Done']);

        return $this->render('projet/show.html.twig', [
            'projet' => $projet,
            'tachesToDo' => $tachesToDo,
            'tachesDoing' => $tachesDoing,
            'tachesDone' => $tachesDone,
        ]);
    }

    #[Route('/{id}/edit', name: 'projet_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Projet $projet, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('projet_show', [
                'id' => $projet->getId(),
            ]);
        }

        return $this->render('projet/edit.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/projets/nouveau', name: 'projet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $projet = new Projet();

        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($projet);
            $em->flush();

            return $this->redirectToRoute('projet_show', [
                'id' => $projet->getId(),
            ]);
        }

        return $this->render('projet/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/projets/{id}/archive', name: 'projet_archive', methods: ['POST'])]
    public function archive(Projet $projet, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('archive_projet_' . $projet->getId(), $request->request->get('_token'))) {
            $projet->setArchive(true);
            $em->flush();
        }

        return $this->redirectToRoute('app_home');
    }
}
