<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserPermissionType;
use App\Form\UserType;
use App\Repository\JoueurRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/admin/user', name: 'app_admin_user')]
    public function index(UserRepository $userRepository): Response
    {

        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/{id<\d+>}/editrole', methods: ['GET', 'POST'], name: 'app_admin_user_editpermission')]
    public function editPermissions(Request $request, User $user,EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserPermissionType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'user.updated_successfully');

            return $this->redirectToRoute('app_admin_user');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id<\d+>}/edit', methods: ['GET', 'POST'], name: 'app_admin_user_edit')]
    public function edit(Request $request, User $user,EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'user.updated_successfully');

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{id}/delete', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager, JoueurRepository $joueurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {

//            $joueur = $joueurRepository->findOneBy(['nom' => $user->getUserIdentifier()]);
            $joueur = $user->getJoueur();

            if (!$joueur) $this->addFlash('warning','Aucun joueur associé au compte');
            else {
//                $this->addFlash('danger','Suppression du compte');
                $entityManager->remove($joueur);
            }
            $entityManager->remove($user);
            $this->addFlash('danger','Suppression du compte');
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_user', [], Response::HTTP_SEE_OTHER);
    }

}
