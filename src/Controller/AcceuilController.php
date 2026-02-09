<?php

namespace App\Controller;

use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class AcceuilController extends AbstractController
{
    private VisiteRepository $visiteRepository;

    public function __construct(VisiteRepository $visiteRepository)
    {
        $this->visiteRepository = $visiteRepository;
    }

    #[Route('/', name: 'accueil')]
    public function index(): Response
    {
        $visites = $this->visiteRepository->findAllOrderBy('dateCreation', 'DESC');

        return $this->render('pages/acceuil.html.twig', [
            'lastVisites' => array_slice($visites, 0, 2),
        ]);
    }
}
