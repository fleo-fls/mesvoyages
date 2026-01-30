<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Visite;
use App\Entity\Environnement;


/**
 * Description of VisiteTest
 *
 * @author jb_mu
 */
class VisiteTest extends TestCase {
    
    public function testGetDateCreationString() {
        $visite = new Visite();
        $visite->setDateCreation(new \DateTime("2026-01-25"));
        $this->assertEquals("25/01/2026", $visite->getDateCreationString());


    }
    
        public function testAddEnvironnement(){
        $environnement = new Environnement();
        $environnement->setNom("plage");
        $visite = new Visite();
        $visite->addEnvironnement($environnement);
        $nbEnvironnementAvant = $visite->getEnvironnements()->count();
        $visite->addEnvironnement($environnement);
        $nbEnvironnementApres = $visite->getEnvironnements()->count();
        $this->assertEquals($nbEnvironnementAvant, $nbEnvironnementApres, "ajout même environnement devrait échouer");
    }
}
