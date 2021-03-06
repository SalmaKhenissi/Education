<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Section;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeanceRepository")
 */
class Seance
{
    const DAY = [0 => 'Lundi', 1 => 'Mardi', 2 => 'Merceredi', 3 => 'Jeudi', 4 => 'Vendredi', 5 => 'Samedi'];

    const Course = [];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\Column(type="string", length=255)
     */
    private $day;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Section", inversedBy="seances")
     */
    private $section;



    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Teacher", inversedBy="seances")
     */
    private $teacher;

    /**
     * @ORM\Column(type="time")
     * @Assert\Time
     * @var string A "H:i:s" formatted value
     */
    private $startAt;

    /**
     * @ORM\Column(type="time")
     * @Assert\Time
     * @var string A "H:i:s" formatted value
     */
    private $finishAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room", inversedBy="seances")
     */
    private $room;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Course", inversedBy="seances")
     */
    private $course;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Discipline", mappedBy="seance")
     */
    private $disciplines;



    public function __construct()
    {
        $this->disciplines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getDayType(): string
    {
        return self::DAY[$this->day];
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }


    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getFinishAt(): ?\DateTimeInterface
    {
        return $this->finishAt;
    }

    public function setFinishAt(\DateTimeInterface $finishAt): self
    {
        $this->finishAt = $finishAt;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    /**
     * @return Collection|Discipline[]
     */
    public function getDisciplines(): Collection
    {
        return $this->disciplines;
    }

    public function addDiscipline(Discipline $discipline): self
    {
        if (!$this->disciplines->contains($discipline)) {
            $this->disciplines[] = $discipline;
            $discipline->setSeance($this);
        }

        return $this;
    }

    public function removeDiscipline(Discipline $discipline): self
    {
        if ($this->disciplines->contains($discipline)) {
            $this->disciplines->removeElement($discipline);
            // set the owning side to null (unless already changed)
            if ($discipline->getSeance() === $this) {
                $discipline->setSeance(null);
            }
        }

        return $this;
    }
}
