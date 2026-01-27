<?php

namespace App\Entity;

use App\Repository\VisiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Attribute as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;




#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: VisiteRepository::class)]
class Visite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[Vich\UploadableField(mapping: 'visites', fileNameProperty: 'imageName', size: 'imageSize')]
    #[Assert\Image(
    mimeTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'],
    mimeTypesMessage: 'Formats autorisés : jpeg, png, gif, svg.')]

    private ?File $imageFile = null;

    // NOTE: This field and the next one need to be nullable, otherwise the deletion won't work
    //       if you want non-nullable fields, set the "erase_fields" option to false in the mapping config
    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 25)]
    private ?string $ville = null;

    #[ORM\Column(length: 25)]
    private ?string $pays = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateCreation = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(min:0, max:20)]
    private ?int $note = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $avis = null;

    #[ORM\Column(nullable: true)]
    private ?int $tempmin = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThan(propertyPath:"tempmin")]
    private ?int $tempmax = null;

    /**
     * @var Collection<int, Environnement>
     */
    #[ORM\ManyToMany(targetEntity: Environnement::class)]
    private Collection $environnements;

    public function __construct()
    {
        $this->environnements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getDateCreation(): ?\DateTime
    {
        return $this->dateCreation;
    }
    
    public function getDateCreationString(): string
    {
    return $this->dateCreation
        ? $this->dateCreation->format('d/m/Y')
        : '';
    }


    public function setDateCreation(?\DateTime $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getAvis(): ?string
    {
        return $this->avis;
    }

    public function setAvis(?string $avis): static
    {
        $this->avis = $avis;

        return $this;
    }

    public function getTempmin(): ?int
    {
        return $this->tempmin;
    }

    public function setTempmin(?int $tempmin): static
    {
        $this->tempmin = $tempmin;

        return $this;
    }

    public function getTempmax(): ?int
    {
        return $this->tempmax;
    }

    public function setTempmax(?int $tempmax): static
    {
        $this->tempmax = $tempmax;

        return $this;
    }

    /**
     * @return Collection<int, Environnement>
     */
    public function getEnvironnements(): Collection
    {
        return $this->environnements;
    }

    public function addEnvironnement(Environnement $environnement): static
    {
        if (!$this->environnements->contains($environnement)) {
            $this->environnements->add($environnement);
        }

        return $this;
    }

    public function removeEnvironnement(Environnement $environnement): static
    {
        $this->environnements->removeElement($environnement);

        return $this;
    }
    
    public function getImageFile(): ?File {
        return $this->imageFile;
    }

    public function getImageName(): ?string {
        return $this->imageName;
    }

    public function getImageSize(): ?int {
        return $this->imageSize;
    }

      public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function setImageName(?string $imageName): void {
        $this->imageName = $imageName;
    }

    public function setImageSize(?int $imageSize): void {
        $this->imageSize = $imageSize;
    }
    
    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context){
        $file = $this->getImageFile();
        if($file != null&& $file!= ""){
            $poids = @filesize($file);
            if($poids != false && $poids > 512000 ){
                $context->buildViolation("Cette image est trop lourde (500ko max)")
                        ->atPath('imageFile')
                        ->addViolation();
            }
        }
        
    }
            


}
