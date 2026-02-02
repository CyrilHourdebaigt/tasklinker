<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Tache;
use App\Form\TacheType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TacheController extends AbstractController
{
    #[Route(
        '/projets/{id}/taches/nouvelle',
        name: 'tache_new',
        requirements: ['id' => '\d+'],
        methods: ['GET', 'POST']
    )]
    public function new(Projet $projet, Request $request, EntityManagerInterface $em): Response
    {
        $tache = new Tache();
        $tache->setProjet($projet);

        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tache);
            $em->flush();

            return $this->redirectToRoute('projet_show', ['id' => $projet->getId()]);
        }

        return $this->render('tache/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    #[Route(
        '/taches/{id}/edit',
        name: 'tache_edit',
        methods: ['GET', 'POST']
    )]
    public function edit(Tache $tache, Request $request, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('projet_show', [
                'id' => $tache->getProjet()->getId(),
            ]);
        }
        

        return $this->render('tache/edit.html.twig', [
            'form' => $form->createView(),
            'tache' => $tache,
        ]);
    }

    #[Route('/taches/{id}/delete', name: 'tache_delete', methods: ['POST'])]
    public function delete(Tache $tache, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_tache_' . $tache->getId(), $request->request->get('_token'))) {
            $projetId = $tache->getProjet()->getId();
            $em->remove($tache);
            $em->flush();

            return $this->redirectToRoute('projet_show', ['id' => $projetId]);
        }

        // si invalide -> on renvoie au projet quand même
        return $this->redirectToRoute('projet_show', ['id' => $tache->getProjet()->getId()]);
    }
}
