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
     * @ORM\ManyToOne(targetEntity="App\Entity\Section", inversedBy="students")
     * @ORM\JoinColumn(nullable=false)
     */
    private $section;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StudentExam", mappedBy="student")
     */
    private $studentExams;

    public function __construct()
    {
        parent::__construct();
        $this->studentExams = new ArrayCollection();
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

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

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

   

   
}
