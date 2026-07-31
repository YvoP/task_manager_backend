<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(UserRepository $userRepository): Response
    {
        $loggedUser = $this->getUser();
        if ($loggedUser === null)
        {
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepository->findOneBy(['username' => $loggedUser->getUserIdentifier()]);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $user,
        ]);
    }
}
