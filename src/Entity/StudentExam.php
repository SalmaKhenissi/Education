<?php

namespace App\Entity;

use App\Entity\Exam;
use App\Entity\Student;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudentExamRepository")
 */
class StudentExam
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $note;

    

   

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Exam", inversedBy="studentExams" )
     */
    private $Exam;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Student", inversedBy="studentExams")
     */
    private $student;

    public function __construct(Exam $exam, Student $student)
    {
        $this->exam=$exam;
        $this->student=$student;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): self
    {
        $this->note = $note;

        return $this;
    }


    

    public function getExam(): ?Exam
    {
        return $this->Exam;
    }

    public function setExam(?Exam $Exam): self
    {
        $this->Exam = $Exam;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }
}
