<?php

namespace App\Tests\Repository;

use App\Entity\Visite;
use App\Repository\VisiteRepository;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class VisiteRepositoryTest extends KernelTestCase
{
    private VisiteRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $this->repository = $container->get(VisiteRepository::class);
    }

    #[Test]
    public function testNbVisites(): void
    {
        $before = $this->repository->count([]);

        $visite = $this->newVisite();
        $this->repository->add($visite, true);

        $this->assertSame($before + 1, $this->repository->count([]));

        $this->repository->remove($visite, true);
        $this->assertSame($before, $this->repository->count([]));
    }

    private function newVisite(): Visite
    {
        return (new Visite())
            ->setVille("New York")
            ->setPays("USA")
            ->setDateCreation(new \DateTime());
    }

    #[Test]
    public function testAddVisite(): void
    {
        $nbVisites = $this->repository->count([]);

        $this->repository->add($this->newVisite(), true);

        $this->assertEquals($nbVisites + 1, $this->repository->count([]), "erreur lors de l'ajout");
    }

    #[Test]
    public function testRemoveVisite(): void
    {
        $visite = $this->newVisite();
        $this->repository->add($visite, true);

        $nbVisites = $this->repository->count([]);

        $this->repository->remove($visite, true);

        $this->assertEquals($nbVisites - 1, $this->repository->count([]), "erreur lors de la suppression");
    }

    #[Test]
    public function testFindByEqualValue(): void
    {
        $uniqueCity = "UniqueCity_" . bin2hex(random_bytes(5)); // e.g., UniqueCity_a1b2c3d4e5
        $visite = $this->newVisite()->setVille($uniqueCity);
    
        $this->repository->add($visite, true);

        $visites = $this->repository->findByEqualValue("ville", $uniqueCity);

        
        $this->assertCount(1, $visites);

        $this->repository->remove($visite, true);
    }

}
