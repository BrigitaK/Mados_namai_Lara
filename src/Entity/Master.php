<?php

namespace App\Entity;

use App\Repository\MasterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MasterRepository::class)
 */
class Master
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="Vardas negali buti tuscias")
     * @Assert\Length(
     *      min = 3,
     *      max = 64,
     *      minMessage = "Vardas per trumpas. Turi but bent {{ limit }} ilgio",
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=64)
    * @Assert\NotBlank(message="Surname should not be blank.")
     * @Assert\Length(
     *      min = 2,
     *      max = 64,
     *      minMessage = "Surname must be at least {{ limit }} characters long.",
     *      maxMessage = "Surname cannot be longer than {{ limit }} characters."
     * )
     */
    private $surname;

    /**
     * @ORM\OneToMany(targetEntity=Outfit::class, mappedBy="master")
     */
    private $outfits;

    public function __construct()
    {
        $this->outfits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return Collection|Outfit[]
     */
    public function getOutfits(): Collection
    {
        return $this->outfits;
    }

    public function addOutfit(Outfit $outfit): self
    {
        if (!$this->outfits->contains($outfit)) {
            $this->outfits[] = $outfit;
            $outfit->setMaster($this);
        }

        return $this;
    }

    public function removeOutfit(Outfit $outfit): self
    {
        if ($this->outfits->removeElement($outfit)) {
            // set the owning side to null (unless already changed)
            if ($outfit->getMaster() === $this) {
                $outfit->setMaster(null);
            }
        }

        return $this;
    }
}
