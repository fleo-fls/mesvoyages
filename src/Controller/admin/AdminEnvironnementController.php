<?php

namespace App\Controller\admin;

use App\Entity\Environnement;
use App\Form\EnvironnementType;
use App\Repository\EnvironnementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminEnvironnementController extends AbstractController
{
    private EnvironnementRepository $repository;

    public function __construct(EnvironnementRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/admin/environnements', name: 'admin.environnements')]
    public function index(): Response
    {
        $environnements = $this->repository->findBy([], ['nom' => 'ASC']);


        return $this->render('admin/admin.environnements.html.twig', [
            'environnements' => $environnements
        ]);
    }

    #[Route('/admin/environnement/suppr/{id}', name:'admin.environnement.suppr')]
    public function suppr(int $id): Response
    {
        $environnement = $this->repository->find($id);

        if (!$environnement) {
            throw $this->createNotFoundException('Environnement introuvable');
        }

        $this->repository->remove($environnement, true);

        return $this->redirectToRoute('admin.environnements');
    }


    #[Route('/admin/environnements/ajout', name: 'admin.environnement.ajout', methods: ['POST'])]
public function ajout(Request $request): Response
{
    $nom = trim((string) $request->request->get('nom'));

    if ($nom === '') {
        return $this->redirectToRoute('admin.environnements');
    }

    $environnement = new Environnement();
    $environnement->setNom($nom);

    $this->repository->add($environnement, true);

    return $this->redirectToRoute('admin.environnements');
}


}

