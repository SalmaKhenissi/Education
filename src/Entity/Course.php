<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CourseRepository")
 */
class Course
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;


    /**
     * @ORM\Column(type="float")
     */
    private $coefficient;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seance", mappedBy="course")
     */
    private $seances;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Level", inversedBy="courses")
     */
    private $level;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Specialty", inversedBy="courses")
     */
    private $specialty;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Exam", mappedBy="course")
     */
    private $exams;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range( min = 1, max = 5)
     */
    private $nbrExams;

    

    

    

    public function __construct()
    {
        $this->seances = new ArrayCollection();
        $this->exams = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->libelle;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

   public function getCoefficient()
    {
        return $this->coefficient;
    }

    public function setCoefficient(float $coefficient)
    {
        $this->coefficient = $coefficient;

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
            $seance->setCourse($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): self
    {
        if ($this->seances->contains($seance)) {
            $this->seances->removeElement($seance);
            // set the owning side to null (unless already changed)
            if ($seance->getCourse() === $this) {
                $seance->setCourse(null);
            }
        }

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(?Level $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getSpecialty(): ?Specialty
    {
        return $this->specialty;
    }

    public function setSpecialty(?Specialty $specialty): self
    {
        $this->specialty = $specialty;

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
            $exam->setCourse($this);
        }

        return $this;
    }

    public function removeExam(Exam $exam): self
    {
        if ($this->exams->contains($exam)) {
            $this->exams->removeElement($exam);
            // set the owning side to null (unless already changed)
            if ($exam->getCourse() === $this) {
                $exam->setCourse(null);
            }
        }

        return $this;
    }

    public function getNbrExams(): ?int
    {
        return $this->nbrExams;
    }

    public function setNbrExams(int $nbrExams): self
    {
        $this->nbrExams = $nbrExams;

        return $this;
    }

   

   

   
}
