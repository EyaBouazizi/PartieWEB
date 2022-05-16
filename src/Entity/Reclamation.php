<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=ReclamationRepository::class)
 */
class Reclamation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("post:read")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="le titre doit etre non vide")
     * @Assert\Length(
     *     min = 4,
     *     minMessage=" il faut min 4 caracteres"
     *
     *     )
     * @ORM\Column(type="string", length=255)
     * @Groups ("post:read")
     */
    private $titre;

    /**
     * @Assert\NotBlank(message="Message doit etre non vide")
     * @Assert\Length(
     *     min = 7,
     *     max = 100,
     *     minMessage="doit etre >=7",
     *     maxMessage="doit etre <=100",
     *
     *     )
     * @ORM\Column(type="string", length=255)
     * @Groups ("post:read")
     */
    private $message;

    /**
     *
     *
     * @ORM\JoinColumn(name="id", referencedColumnName="id_user")
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="reclamations")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ("post:read")
     */
    private $etat;

    /**
     * @ORM\Column(type="datetime", nullable=true , options={"default": "CURRENT_TIMESTAMP"} )
     * @Groups ("post:read")
     */
    private $dateRec;

    /**
     * @ORM\OneToOne(targetEntity=Reponse::class, mappedBy="reclamation", cascade={"persist", "remove"})
     */
    private $reponse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDateRec(): ?\DateTimeInterface
    {
        return $this->dateRec;
    }

    public function setDateRec(\DateTimeInterface $dateRec): self
    {
        $this->dateRec = $dateRec;

        return $this;
    }
    public function getUser(): ?Utilisateur
    {
        return $this->user;
    }

    public function setUser(?Utilisateur $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getReponse(): ?Reponse
    {
        return $this->reponse;
    }

    public function setReponse(?Reponse $reponse): self
    {
        // unset the owning side of the relation if necessary
        if ($reponse === null && $this->reponse !== null) {
            $this->reponse->setReclamation(null);
        }

        // set the owning side of the relation if necessary
        if ($reponse !== null && $reponse->getReclamation() !== $this) {
            $reponse->setReclamation($this);
        }

        $this->reponse = $reponse;

        return $this;
    }


}
