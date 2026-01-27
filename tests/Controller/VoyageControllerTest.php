<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of VoyageControllerTest
 *
 * @author jb_mu
 */
class VoyageControllerTest extends WebTestCase {
    
    public function testAccesPage(){
        $client= static::createClient();
        $client->request('GET','/voyages');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    public function testContenuPage(){
        $client = static::createClient();
        $crawler = $client->request('GET','/voyages');
        $this->assertSelectorTextContains('thead th', 'Ville');
        $this->assertCount(4, $crawler->filter('th'));
                  
    }
    
    public function testLinkVille(){
        $client = static::createClient();
        $client->request('GET','/voyages');
        $client->clickLink('Swan');
        $response = $client->getResponse();
        $this->assertEquals($response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/voyages/29', $uri);
    }
    
   
}
