<?php

namespace App\Entity;

use App\Entity\Room;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomRepository")
 */
class Room
{

    const TYPE = [
        0 => 'Salle',
        1 => 'Laboratoire' 
        
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seance", mappedBy="room")
     */
    private $seances;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Exam", mappedBy="room")
     */
    private $exams;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    public function __toString(){
        if($this->getType()== 0)
        {$room='S'.$this->number;}
        else{ $room='L'.$this->number;}
        return $room;
     }

    public function __construct()
    {
        $this->seances = new ArrayCollection();
        $this->exams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection|Seance[]
     */
    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): self
    {
        if (!$this->seances->contains($seance)) {
            $this->seances[] = $seance;
            $seance->setRoom($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): self
    {
        if ($this->seances->contains($seance)) {
            $this->seances->removeElement($seance);
            // set the owning side to null (unless already changed)
            if ($seance->getRoom() === $this) {
                $seance->setRoom(null);
            }
        }

        return $this;
    }

    


    /**
     * @return Collection|Exam[]
     */
    public function getExams(): Collection
    {
        return $this->exams;
    }

    public function addExam(Exam $exam): self
    {
        if (!$this->exams->contains($exam)) {
            $this->exams[] = $exam;
            $exam->setRoom($this);
        }

        return $this;
    }

    public function removeExam(Exam $exam): self
    {
        if ($this->exams->contains($exam)) {
            $this->exams->removeElement($exam);
            // set the owning side to null (unless already changed)
            if ($exam->getRoom() === $this) {
                $exam->setRoom(null);
            }
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }
    public function getTypeType(): string
    {
        return self::TYPE[$this->type];
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
