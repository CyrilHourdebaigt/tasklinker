<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Repository\ProjetRepository;
use App\Repository\TacheRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
