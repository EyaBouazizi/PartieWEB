<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ClubRepository::class)
 */
class Club
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;


    /**
     * @Assert\NotBlank(message="nom doit Ãªtre non vide")
     * @Assert\Length(
     *     min = 5,
     *     minMessage="Entrer un nom au mininmum de 5 caractÃ©res"
     *
     * )
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    public $nom_club;


    /*
     * @Assert\NotBlank (message="il faut remplir le champs")
     * @ORM\Column(type="datetime")
     */
    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups("post:read")
     */
    public $date_creation;

    /**
     * @Assert\NotBlank(message="owner doit Ãªtre non vide")
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    public $club_owner;



    /*
    * @Assert\NotBlank(message="il faut choisir une image")
     * @Assert\File(mimeTypes={"image/png", "image/jpeg"})
     */
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $imageclb;


    /**
     * @Assert\NotBlank(message="Il faut choisir un type")
     * @ORM\Column(name="access" ,type="boolean" ,options={"default":true})
     * @Groups("post:read")
     */
    private $access;

    /**
     * @ORM\OneToMany(targetEntity=Evenement::class, mappedBy="club" , orphanRemoval=true)
     */
    private $evenements;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom_club(): ?string
    {
        return $this->nom_club;
    }

    public function setNomClub(string $nom_club): self
    {
        $this->nom_club = $nom_club;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getclub_owner(): ?string
    {
        return $this->club_owner;
    }

    public function setClubOwner(string $club_owner): self
    {
        $this->club_owner = $club_owner;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * @param mixed $access
     */
    public function setAccess($access): void
    {
        $this->access = $access;
    }





    public function getImageclb()
    {
        return $this->imageclb;
    }


    public function setImageclb($imageclb)
    {
        $this->imageclb = $imageclb;
        return $this;
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements[] = $evenement;
            $evenement->setClub($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getClub() === $this) {
                $evenement->setClub(null);
            }
        }

        return $this;
    }


    public function __toString()
    {
        return(string)$this->getNom_club();
    }




}
