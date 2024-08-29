<?php
// src/Controller/SecurityController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, redirigez-le vers une autre page, par exemple, la page d'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // Récupère l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Le dernier nom d'utilisateur entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Ce code ne sera jamais exécuté, Symfony gère automatiquement la déconnexion
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
