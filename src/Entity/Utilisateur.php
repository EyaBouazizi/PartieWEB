<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this @email")
 */
class Utilisateur implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     *
     */
    private $idUser;

    /**
     * @var string
     * @Assert\NotBlank
     *
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     * @ORM\Column(name="email", type="string", length=30, nullable=false, unique=true)
     *
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Your Username must be at least {{ limit }} characters long",
     *      maxMessage = "Your Username cannot be longer than {{ limit }} characters")
     * @ORM\Column(name="username", type="string", length=30, nullable=false, unique=true)
     */
    private $username;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      max = 50,
     *      minMessage = "Your Username must be at least {{ limit }} characters long",
     *      maxMessage = "Your Username cannot be longer than {{ limit }} characters")
     *
     *
     * @ORM\Column(name="password", type="text", length=65535, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_prenom", type="string", length=30, nullable=false)
     */
    private $nomPrenom;

    /**
     * @var \DateTime
     * @Assert\NotBlank
     * @ORM\Column(name="age", type="date", nullable=false)
     *
     *
     */
    private $age;

    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="type", type="string", length=20, nullable=false)
     */
    private $type;


    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getNomPrenom(): ?string
    {
        return $this->nomPrenom;
    }


    public function setNomPrenom(string $nomPrenom): self
    {
        $this->nomPrenom = $nomPrenom;

        return $this;
    }

    public function getAge(): ?\DateTimeInterface
    {
        return $this->age;
    }

    public function setAge(\DateTimeInterface $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getRoles(): ?array
    {
        $roles = [];

        if ($this->getType() == 'admin')
            $roles[] = 'ROLE_ADMIN';
        if ($this->getType() == 'user')
            $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
