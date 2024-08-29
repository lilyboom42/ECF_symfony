<?php
// src/Controller/RegistrationController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            // Set additional properties if necessary (e.g., roles, registration date)
            $user->setRoles(['ROLE_USER']);

            // Persist the new user
            $entityManager->persist($user);
            $entityManager->flush();

            // Add a flash message
            $this->addFlash('success', 'Registration successful! Please check your email to verify your account.');

            // Redirect to the login page or wherever appropriate
            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/register.html.twig', [
            'userFormType' => $form->createView(), // Corrigez ici le nom pour correspondre à celui utilisé dans le template
        ]);
    }

    #[Route('/success', name: 'app_success')]
    public function success(): Response
    {
        return $this->render('register/success.html.twig');
    }
}
