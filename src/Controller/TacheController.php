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
    // Création d'une nouvelle pour un projet
    #[Route(
        '/projets/{id}/taches/nouvelle',
        name: 'tache_new',
        requirements: ['id' => '\d+'],
        methods: ['GET', 'POST']
    )]
    public function new(Projet $projet, Request $request, EntityManagerInterface $em): Response
    {
        // Je crée une nouvelle tâche vide
        $tache = new Tache();
        // Je rattache cette tâche au projet que j’ai reçu dans l’URL
        $tache->setProjet($projet);

        // Je crée le formulaire lié à ma tâche
        $form = $this->createForm(TacheType::class, $tache);
        // Je récupère les données envoyées
        $form->handleRequest($request);

        // Si le formulaire a été envoyé et qu’il est valide, j'enregistre en BDD
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tache);
            $em->flush();

            // Je retourne sur la page du projet
            return $this->redirectToRoute('projet_show', ['id' => $projet->getId()]);
        }

        return $this->render('tache/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    // Modification d’une tâche existante
    #[Route(
        '/taches/{id}/edit',
        name: 'tache_edit',
        methods: ['GET', 'POST']
    )]
    public function edit(Tache $tache, Request $request, EntityManagerInterface $em): Response
    {

        // Je crée le formulaire lié à la tâche existante
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        // Ici je n’ai pas besoin de persist() car l’objet existe déjà
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

    // Suppression d'une tâche
    #[Route('/taches/{id}/delete', name: 'tache_delete', methods: ['POST'])]
    public function delete(Tache $tache, Request $request, EntityManagerInterface $em): Response
    {
        // Je vérifie que le token CSRF reçu correspond bien à celui attendu
        if ($this->isCsrfTokenValid('delete_tache_' . $tache->getId(), $request->request->get('_token'))) {
            // Je garde l’id du projet avant la suppression
            $projetId = $tache->getProjet()->getId();
            // Je supprime la tâche en BDD
            $em->remove($tache);
            $em->flush();

            // Après suppression, je reviens sur la page du projet
            return $this->redirectToRoute('projet_show', ['id' => $projetId]);
        }

        // si invalide -> on renvoie au projet quand même
        return $this->redirectToRoute('projet_show', ['id' => $tache->getProjet()->getId()]);
    }
}
