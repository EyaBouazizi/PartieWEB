<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Favoris
 *
 * @ORM\Table(name="favoris", indexes={@ORM\Index(name="id_user", columns={"id_user"}), @ORM\Index(name="id_livre", columns={"id_livre"})})
 * @ORM\Entity
 */
class Favoris
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_fav", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idFav;

    /**
     * @var \Livre
     *
     * @ORM\ManyToOne(targetEntity="Livre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_livre", referencedColumnName="id_livre")
     * })
     */
    private $idLivre;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id_user", onDelete="CASCADE")
     * })
     */
    private $idUser;

    public function getIdFav(): ?int
    {
        return $this->idFav;
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
