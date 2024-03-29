<?php

namespace App\Controller;
use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use App\Form\UserType;
class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout()
    {
        
        // throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }

    #[Route(path: '/createUser', name: 'app_user')]
    public function createUser(EntityManagerInterface $doctrine, UserPasswordHasherInterface $passwordHasher)
    {
        $admin = new User();
        $admin->setUsername('YoelAdmin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($passwordHasher->hashPassword($admin,'123456+'));

        
        $doctrine->persist($admin);
        $doctrine->flush();
    }

}
