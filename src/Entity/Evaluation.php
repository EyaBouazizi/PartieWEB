<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Serializer\Annotation\Groups;
/**
 * Evaluation
 *
 * @ORM\Table(name="evaluation", indexes={@ORM\Index(name="fk_livre_evaluation", columns={"id_livre"})})
 * @ORM\Entity(repositoryClass="App\Repository\EvaluationRepository")
 */

class Evaluation
{
    /**
     * @var int
     * * @ORM\Id
     *
    *
     * @ORM\Column(name="id_evaluation", type="integer", nullable=false)

     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("post:read")
     *
     */
    private $idEvaluation;

    /**
     * @var int
     * @Assert\Range(
     *      min = 1,
     *      max = 5,
     *      notInRangeMessage = "You must choose between 1 and 5 stars to enter",
     * )
     * @ORM\Column(name="nb_stars", type="integer", nullable=false)
     *
     */
    private $nbStars;

    /**
     * @var \App\Entity\Livre
     *
     * @ORM\ManyToOne(targetEntity="Livre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_livre", referencedColumnName="id_livre")
     * })

     */
    private $idLivre;
    private $isEvaluated;

    public function getIdEvaluation(): ?int
    {
        return $this->idEvaluation;
    }

    public function getNbStars(): ?int
    {
        return $this->nbStars;
    }

    public function setNbStars(int $nbStars): self
    {
        $this->nbStars = $nbStars;

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

    /**
     * @return mixed
     */
    public function getIsEvaluated()
    {
        return $this->isEvaluated;
    }

    /**
     * @param mixed $isEvaluated
     */
    public function setIsEvaluated($isEvaluated): void
    {
        $this->isEvaluated = $isEvaluated;
    }


}
