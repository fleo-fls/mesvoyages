<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Controller;

use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Visite;

/**
 * Description of VoyagesController
 *
 * @author jb_muller et CNED
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
        $visites = $this->repository->findAllOrderBy('dateCreation', 'DESC'); 
        return $this->render('pages/voyages.html.twig', [
            'visites' => $visites
        ]);
    }
    
#[Route('/voyages/tri/{champ}/{ordre}', name: 'voyages.sort')]
public function sort($champ, $ordre): Response{
    $visites = $this->repository->findAllOrderBy($champ, $ordre);
    return $this->render("pages/voyages.html.twig", ['visites'=> $visites]);
}

#[Route('/voyages/recherche/{champ}', name: 'voyages.findallequal')]
public function findallequal($champ, Request $request): Response{
    if($this->isCsrfTokenValid('filtre_', $champ, $request->get('_token'))){
        $valeur = $request->get("recherche");
        $visites = $this->repository->findByEqualValue($champ, $valeur);
        return $this->render("pages/voyages.html.twig", ['visites'=> $visites]);
    }
    return $this->redirectToRoute("voyages");
}

#[Route(
    '/voyages/{id}',
    name: 'voyages.showone',
    requirements: ['id' => '\d+']
)]
public function showone(Visite $visite): Response
{
    return $this->render('pages/voyage.html.twig', [
        'visite' => $visite
    ]);
}


     
 }
