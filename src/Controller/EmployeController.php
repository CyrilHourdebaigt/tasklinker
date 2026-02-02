<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmployeController extends AbstractController
{
    #[Route('/employes', name: 'employe_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $employes = $em->getRepository(Employe::class)->findAll();

        return $this->render('employe/index.html.twig', [
            'employes' => $employes,
        ]);
    }

    #[Route('/employes/{id}/edit', name: 'employe_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Employe $employe, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('employe_index');
        }

        return $this->render('employe/edit.html.twig', [
            'employe' => $employe,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/employes/{id}/delete', name: 'employe_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Employe $employe, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_employe_' . $employe->getId(), $request->request->get('_token'))) {
            $em->remove($employe);
            $em->flush();
        }

        return $this->redirectToRoute('employe_index');
    }
}
