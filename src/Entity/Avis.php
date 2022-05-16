<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Entity\Utilisateur;
use App\Entity\Livre;
use Symfony\Component\Serializer\Annotation\Groups;
use Vangrg\ProfanityBundle\Validator\Constraints as ProfanityAssert;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Avis
 *
 * @ORM\Table(name="avis", indexes={@ORM\Index(name="id_livre", columns={"id_livre"}), @ORM\Index(name="id_user", columns={"id_user"})})
 * @ORM\Entity(repositoryClass="App\Repository\AvisRepository")
 */
class Avis
{
    /**
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var int
     * @ORM\Id
     * @ORM\Column(name="id_avis", type="integer", nullable=false)
     * @Groups ("post:read")
     */
    private $idAvis;


    /**
     * @var string
     * @Assert\NotBlank(message="this field can't be empty")
     * @Assert\Length(
     *      min = 3,
     *      max = 500,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(name="commentaire", type="string", length=500, nullable=false)
     * @ProfanityAssert\ProfanityCheck
     */
    private $commentaire;

    /**
     * @var \App\Entity\Livre
     *
     * @ORM\ManyToOne(targetEntity="Livre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_livre", referencedColumnName="id_livre")
     * })
     */
    private $idLivre;

    /**
     * @var \App\Entity\Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $idUser;

    public function getIdAvis(): ?int
    {
        return $this->idAvis;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getIdLivre(): ?Livre
    {
        return $this->idLivre;
    }

    public function setIdLivre(?Livre $idLivre): self
    {
        $this->idLivre = $idLivre;

        return $this;
    }

    public function getIdUser(): ?Utilisateur
    {
        return $this->idUser;
    }

    public function setIdUser(?Utilisateur $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }


}
