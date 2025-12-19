<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Controller;

use Symfony\Component\Routing\Annotation\route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of AcceuilController
 *
 * @author jb_mu
 */
class AcceuilController {
    #[Route('/', name: 'accueil')]
    public function index(): Response {
        return new Response('Hello Word!');
        
    }
}
