<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Validations;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Visite;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Description of VisiteValidationsTest
 *
 * @author jb_mu
 */
class VisiteValidationsTest extends KernelTestCase {
    
    public function getVisite():Visite {
        return (new Visite())
        ->setVille("New York")
        ->setPays("USA");    
    }
    
    public function testValidNoteVisite(){
        $visite = $this->getVisite()->setNote(10);
        $this->assertErrors($visite, 0);
        $this->assertErrors($this->getVisite()->setNote(10), 0, "10 devrait réussir");
        $this->assertErrors($this->getVisite()->setNote(0), 0, "0 devrait réussir");
        $this->assertErrors($this->getVisite()->setNote(20), 0, "20 devrait réussir");
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($visite, null, ['visite']);
        $this->assertCount(0, $error);
    }
    
    public function assertErrors(Visite $visite, int $nbErreursAttendues, string $message=""){
    self::bootKernel();
    $validator = self::getContainer()->get(ValidatorInterface::class);
    $error = $validator->validate($visite, null, ['Default', 'visite']);
    $this->assertCount($nbErreursAttendues, $error, $message);
    }

    
    public function testNonValidNoteVisite(){
        $visite = $this->getVisite()->setNote(21);
        $this->assertErrors($visite, 1);
        $this->assertErrors($this->getVisite()->setNote(21), 1, "21 devrait échouer");
        $this->assertErrors($this->getVisite()->setNote(-1), 1, "-1 devrait échouer");
        $this->assertErrors($this->getVisite()->setNote(-5), 1, "-5 devrait échouer");
        $this->assertErrors($this->getVisite()->setNote(25), 1, "25 devrait échouer");
    }
    
    public function testValidTempmaxVisite(){    
        $this->assertErrors($this->getVisite()->setTempmin(18)->setTempmax(20), 0, "min=18, max=20 devrait réussir");
        $this->assertErrors($this->getVisite()->setTempmin(19)->setTempmax(20), 0, "min=19, max=20 devrait réussir");
    }
    
    public function testNonValidTempMaxVisite(){
        $visite = $this->getVisite()
                ->setTempmin(20)
                ->setTempmax(18);
        $this->assertErrors($visite, 1, "min=20 max=18 devrait échouer");
        $this->assertErrors($this->getVisite()->setTempmin(20)->setTempmax(20), 1, "min=20, max=20 devrait échouer");
    }
    
    public function testValidDatecreationVisite(){ 
        $aujourdhui = new \DateTime();
        $this->assertErrors($this->getVisite()->setDateCreation($aujourdhui), 0, "aujourd'hui devrait réussir");
        $plustot = (new \DateTime())->sub(new \DateInterval("P5D"));
        $this->assertErrors($this->getVisite()->setDateCreation($plustot), 0, "plus tôt devrait réussir");
    }
    
     public function testNonValidDatecreationVisite(){ 
        $demain = (new \DateTime())->add(new \DateInterval("P1D"));
        $this->assertErrors($this->getVisite()->setDateCreation($demain), 1, "demain devrait échouer");
        $plustard = (new \DateTime())->add(new \DateInterval("P5D"));
        $this->assertErrors($this->getVisite()->setDateCreation($plustard), 1, "plus tard devrait échouer");
    } 
}
