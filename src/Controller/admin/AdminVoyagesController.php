<?php

namespace App\Controller\admin;

use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Form\VisiteType;
use Symfony\Component\HttpFoundation\Request;


class AdminVoyagesController extends AbstractController
{
    private VisiteRepository $repository;

    public function __construct(VisiteRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/admin', name: 'admin.voyages')]
    public function index(): Response
    {
        $visites = $this->repository->findAllOrderBy('datecreation', 'DESC');

        return $this->render('admin/admin.voyages.html.twig', [
            'visites' => $visites
        ]);
    }
    
#[Route('/admin/suppr/{id}', name:'admin.voyage.suppr')]
public function suppr(int $id): Response
{
    $visite = $this->repository->find($id);

    if (!$visite) {
        throw $this->createNotFoundException('Visite introuvable');
    }

    $this->repository->remove($visite, true);

    return $this->redirectToRoute('admin.voyages');
}

#[Route('/admin/edit/{id}', name: 'admin.voyage.edit')]
public function edit(int $id, Request $request): Response
{
    $visite = $this->repository->find($id);

    if (!$visite) {
        throw $this->createNotFoundException('Visite introuvable');
    }

    $formVisite = $this->createForm(VisiteType::class, $visite);
    $formVisite->handleRequest($request);

    if ($formVisite->isSubmitted() && $formVisite->isValid()) {
        $this->repository->add($visite); // ← flush en BDD
        return $this->redirectToRoute('admin.voyages');
    }

    return $this->render('admin/admin.voyage.edit.html.twig', [
        'visite' => $visite,
        'formvisite' => $formVisite->createView(),
    ]);
}


}

