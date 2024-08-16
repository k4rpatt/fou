<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\User;
use App\Form\JoueurType;
use App\Repository\JoueurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/joueur')]
class JoueurController extends AbstractController
{
    #[Route('/', name: 'app_joueur_index', methods: ['GET'])]
    public function index(JoueurRepository $joueurRepository): Response
    {
        return $this->render('joueur/index.html.twig', [
            'joueurs' => $joueurRepository->findAll(),
        ]);
    }



    #[Route('/show/{id}', name: 'app_joueur_show', methods: ['GET'])]
    #[IsGranted('ROLE_OFFICIER')]
    public function show(Joueur $joueur, JoueurRepository $joueurRepository): Response
    {
//        $user = $this->getUser();
//        $joueur = $joueurRepository->find($id);

        return $this->render('joueur/show.html.twig', [
            'joueur' => $joueur,
        ]);
    }

    #[Route('/profil', name: 'app_joueur_profil', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function profil(JoueurRepository $joueurRepository, EntityManagerInterface   $entityManager): Response
    {
        $user = $this->getUser();

//            $this->addFlash('info', 'on cherche '.$user->getUserIdentifier());
//        $joueur = $joueurRepository->findOneBy(['nom' => $user->getUserIdentifier()]);
        $joueur = $user->getJoueur();
        if (!$joueur) {
            $this->addFlash('warning', 'aucun joueur ne correspond à votre nom !');
            $joueur = new Joueur();
            $joueur->setNom($user->getUserIdentifier());
            $this->addFlash('success', 'Création du joueur');
            $user->setJoueur($joueur);
            $entityManager->persist($joueur);
            $entityManager->flush();
        }

//        $joueur = $joueurRepository->find(1);

        return $this->render('joueur/show.html.twig', [
            'joueur' => $joueur,
        ]);
    }


    #[Route('/new', name: 'app_joueur_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_OFFICIER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $joueur = new Joueur();
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($joueur);
            $entityManager->flush();

            return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('joueur/new.html.twig', [
            'joueur' => $joueur,
            'form' => $form,
        ]);
    }



    #[Route('/{id}/edit', name: 'app_joueur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Joueur $joueur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('joueur/edit.html.twig', [
            'joueur' => $joueur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_joueur_delete', methods: ['POST'])]
    public function delete(Request $request, Joueur $joueur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$joueur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($joueur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
    }
}
