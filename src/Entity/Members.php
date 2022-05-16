<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Members
 *
 * @ORM\Table(name="members", indexes={@ORM\Index(name="id_user", columns={"id_user"}), @ORM\Index(name="id_club", columns={"id_club"})})
 * @ORM\Entity
 */
class Members
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idUser;

    /**
     * @var int
     *
     * @ORM\Column(name="id_club", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idClub;


}
