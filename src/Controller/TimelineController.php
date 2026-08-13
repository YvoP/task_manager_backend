<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/timeline', name: 'app_timeline')]
final class TimelineController extends AbstractController
{
    #[Route('/{id:project}', name: '_project')]
    public function index(Project $project): Response
    {
        return $this->render('timeline/index.html.twig', [
            'controller_name' => 'TimelineController',
            'tasks' => $project->getTasks(),
        ]);
    }
}
