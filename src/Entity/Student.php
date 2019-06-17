<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudentRepository")
 */
class Student extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Guardian", inversedBy="children" )
     * @ORM\JoinColumn(nullable=false)
     */
    private $guardian;

    

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Section", inversedBy="students")
     */
    private $sections;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StudentExam", mappedBy="student")
     */
    private $studentExams;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Discipline", mappedBy="student")
     */
    private $disciplines;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Punishment", mappedBy="students")
     */
    private $punishments;

    

    public function __construct()
    {
        parent::__construct();
        $this->sections = new ArrayCollection();
        $this->studentExams = new ArrayCollection();
        $this->disciplines = new ArrayCollection();
        $this->punishments = new ArrayCollection();
    }

    /**
     * @return (Role|string)[]
     */
    public function getRoles()
    {
        return ['ROLE_STUDENT'];

    }

   

    public function getGuardian(): ?Guardian
    {
        return $this->guardian;
    }

    public function setGuardian(?Guardian $guardian): self
    {
        $this->guardian = $guardian;

        return $this;
    }

    

    

    /**
     * @return Collection|Section[]
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(Section $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->contains($section)) {
            $this->sections->removeElement($section);
        }

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Collection|StudentExam[]
     */
    public function getStudentExams(): Collection
    {
        return $this->studentExams;
    }

    public function addStudentExam(StudentExam $studentExam): self
    {
        if (!$this->studentExams->contains($studentExam)) {
            $this->studentExams[] = $studentExam;
            $studentExam->setStudent($this);
        }

        return $this;
    }

    public function removeStudentExam(StudentExam $studentExam): self
    {
        if ($this->studentExams->contains($studentExam)) {
            $this->studentExams->removeElement($studentExam);
            // set the owning side to null (unless already changed)
            if ($studentExam->getStudent() === $this) {
                $studentExam->setStudent(null);
            }
        }

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
            $discipline->setStudent($this);
        }

        return $this;
    }

    public function removeDiscipline(Discipline $discipline): self
    {
        if ($this->disciplines->contains($discipline)) {
            $this->disciplines->removeElement($discipline);
            // set the owning side to null (unless already changed)
            if ($discipline->getStudent() === $this) {
                $discipline->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Punishment[]
     */
    public function getPunishments(): Collection
    {
        return $this->punishments;
    }

    public function addPunishment(Punishment $punishment): self
    {
        if (!$this->punishments->contains($punishment)) {
            $this->punishments[] = $punishment;
            $punishment->setStudent($this);
        }

        return $this;
    }

    public function removePunishment(Punishment $punishment): self
    {
        if ($this->punishments->contains($punishment)) {
            $this->punishments->removeElement($punishment);
            // set the owning side to null (unless already changed)
            if ($punishment->getStudent() === $this) {
                $punishment->setStudent(null);
            }
        }

        return $this;
    }

   

   
}
