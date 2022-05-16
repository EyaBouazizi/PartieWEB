<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 */
class Participant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idu;

    /**
     * @ORM\Column(type="integer")
     */
    private $ide;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdu(): ?int
    {
        return $this->idu;
    }

    public function setIdu(int $idu): self
    {
        $this->idu = $idu;

        return $this;
    }

    public function getIde(): ?int
    {
        return $this->ide;
    }

    public function setIde(int $ide): self
    {
        $this->ide = $ide;

        return $this;
    }
}
