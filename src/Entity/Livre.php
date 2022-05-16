<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Livre
 *
 * @ORM\Table(name="livre", indexes={@ORM\Index(name="id_categorie", columns={"id_categorie"})})
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Livre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_livre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLivre;

    /**
     * @var string
     * @Assert\NotBlank(message="nom doit Ãªtre non vide")
     * @Assert\Length(
     *     min = 5,
     *     minMessage="Entrer un nom au mininmum de 5 caractÃ©res"
     *
     * )
     * @ORM\Column(name="titre", type="string", length=30, nullable=false)
     */
    private $titre;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      max = 50,
     *      minMessage = "Your Username must be at least {{ limit }} characters long",
     *      maxMessage = "Your Username cannot be longer than {{ limit }} characters")
     * @ORM\Column(name="auteur", type="string", length=30, nullable=false)
     */
    private $auteur;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      max = 700,
     *      minMessage = "Your Username must be at least {{ limit }} characters long",
     *      maxMessage = "Your Username cannot be longer than {{ limit }} characters")
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      max = 50,
     *      minMessage = "Your Username must be at least {{ limit }} characters long",
     *      maxMessage = "Your Username cannot be longer than {{ limit }} characters")
     * @ORM\Column(name="text_livre", type="text", length=65535, nullable=false)
     */
    private $textLivre;

    /**
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie", referencedColumnName="id_categorie")
     * })
     */
    private $idCategorie;



    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function __construct()
    {
        $this->date = new \DateTime() ;
    }

    public function getIdLivre(): ?int
    {
        return $this->idLivre;
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

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getTextLivre(): ?string
    {
        return $this->textLivre;
    }

    public function setTextLivre(string $textLivre): self
    {
        $this->textLivre = $textLivre;

        return $this;
    }

    public function getIdCategorie(): ?Categorie
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(?Categorie $idCategorie): self
    {
        $this->idCategorie = $idCategorie;

        return $this;
    }



    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }


}
