<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Controller;

use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of AcceuilController
 *
 * @author jb_mu
 */
class VoyagesController extends AbstractController
{
    private VisiteRepository $repository;

    public function __construct(VisiteRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/voyages', name: 'voyages')]
    public function index(): Response
    {
        // Exemple d'utilisation du repository
        // $visites = $this->repository->findAll();
        $visites = $this->repository->findAllOrderBy('datecreation', 'DESC'); 
        return $this->render('pages/voyages.html.twig', [
            'visites' => $visites
        ]);
    }
    
#[Route('/voyages/tri/{champ}/{ordre}', name: 'voyages.sort')]
public function sort($champ, $ordre): Response{
    $visites = $this->repository->findAllOrderBy($champ, $ordre);
    return $this->render("pages/voyages.html.twig", ['visites'=> $visites]);
}
}
