<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\TimeType;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
{




    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    public $id_event;


    /**
     * @return mixed
     */
    public function getid_event()
    {
        return $this->id_event;
    }

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
    public $event_name;

    /**
     * @Assert\NotBlank (message="description doit Ãªtre non vide")
     * @Assert\Length (
     *     min = 7,
     *     max = 100,
     *     minMessage = " le nom doit Ãªtre supÃ©rieur ou Ã©gale Ã  7 ",
     *     maxMessage = " le nom doit Ãªtre infÃ©rieur ou Ã©gale Ã  100 ")
     * @ORM\Column(type="string", length=2000)
     * @Groups("post:read")
     */
    public $description;

    /**
     * @Assert\NotBlank (message="date doit Ãªtre supÃ©rieur Ã  date actuelle")
     * @Assert\GreaterThan("today")
     * @ORM\Column(type="datetime")
     * @Groups("post:read")
     */
    public $event_date;

    /**
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     * @Groups("post:read")
     */
    private $adresse;

    /*
     *
     * @ORM\Column(name="nbrparticipants", type="integer")
     * @Groups("post:read")
     *
    private $nbrparticipants=0;*/

    /**
     *
     * @ORM\Column(name="nbrparticiMax", type="integer")
     * @Groups("post:read")
     */
    private $nbrparticiMax=0;


    /*

     * @Assert\NotBlank (message="Il faut choisir une image")
     * @Assert\File(mimeTypes={"image/png", "image/jpeg"})
    */
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="evenements")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("post:read")
     */
    private $club;

    /**
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="ide")

     */
    private $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getNbrparticiMax(): int
    {
        return $this->nbrparticiMax;
    }

    /**
     * @param int $nbrparticiMax
     */
    public function setNbrparticiMax(int $nbrparticiMax): void
    {
        $this->nbrparticiMax = $nbrparticiMax;
    }





    public function getevent_name(): ?string
    {
        return $this->event_name;
    }

    public function setEventName(string $event_name): self
    {
        $this->event_name = $event_name;

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

    /**
     * @return mixed
     */
    public function getEventDate()
    {
        return $this->event_date;
    }

    /**
     * @param mixed $event_date
     */
    public function setEventDate($event_date): void
    {
        $this->event_date = $event_date;
    }



    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse): void
    {
        $this->adresse = $adresse;
    }

    /*
        public function getStartingTime() : ?\DateTimeInterface
        {
            return $this->startingTime;
        }


        public function setStartingTime(?\DateTimeInterface $startingTime): self
        {
            $this->startingTime = $startingTime;
        }



        public function getEndingTime()
        {
            return $this->endingTime;
        }

        /**
         * @param DateTime $endingTime

        public function setEndingTime(DateTime $endingTime): void
        {
            $this->endingTime = $endingTime;
        }

    */


    /*
     * @return int
     *
    public function getNbrparticipants(): int
    {
        return $this->nbrparticipants;
    }*/

    /*
     * @param int $nbrparticipants
     *
    public function setNbrparticipants(int $nbrparticipants): void
    {
        $this->nbrparticipants = $nbrparticipants;
    }*/







    public function getImage()
    {
        return $this->image;
    }

    public function setImage( $image)
    {
        $this->image = $image;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
    }



    public function __toString()
    {
        return(string)$this->getevent_name();
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setIde($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getIde() === $this) {
                $participant->setIde(null);
            }
        }

        return $this;
    }


}
