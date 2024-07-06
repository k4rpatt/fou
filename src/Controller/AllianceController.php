<?php

namespace App\Controller;

use App\Entity\Alliance;
use App\Entity\Serveur;
use App\Form\AllianceType;
use App\Repository\AllianceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/alliance')]
class AllianceController extends AbstractController
{
    #[Route('/', name: 'app_alliance_index', methods: ['GET'])]
    public function index(AllianceRepository $allianceRepository): Response
    {
        return $this->render('alliance/index.html.twig', [
            'alliances' => $allianceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_alliance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $alliance = new Alliance();
        $form = $this->createForm(AllianceType::class, $alliance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($alliance);
            $entityManager->flush();

            return $this->redirectToRoute('app_alliance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('alliance/new.html.twig', [
            'alliance' => $alliance,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_alliance_show', methods: ['GET'])]
    public function show(Alliance $alliance): Response
    {
        return $this->render('alliance/show.html.twig', [
            'alliance' => $alliance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_alliance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Alliance $alliance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AllianceType::class, $alliance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_alliance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('alliance/edit.html.twig', [
            'alliance' => $alliance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_alliance_delete', methods: ['POST'])]
    public function delete(Request $request, Alliance $alliance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$alliance->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($alliance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_alliance_index', [], Response::HTTP_SEE_OTHER);
    }
}
