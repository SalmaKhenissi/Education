<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExamRepository")
 */
class Exam
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
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $passAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $finishAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quarter", inversedBy="exams")
     */
    private $quarter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Course", inversedBy="exams")
     */
    private $course;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StudentExam", mappedBy="exam")
     */
    private $studentExams;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Section", inversedBy="exams")
     */
    private $section;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room", inversedBy="exams")
     */
    private $room;

    public function __construct()
    {
        $this->studentExams = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getPassAt(): ?\DateTimeInterface
    {
        return $this->passAt;
    }

    public function setPassAt(\DateTimeInterface $passAt): self
    {
        $this->passAt = $passAt;

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

    public function getQuarter(): ?Quarter
    {
        return $this->quarter;
    }

    public function setQuarter(?Quarter $quarter): self
    {
        $this->quarter = $quarter;

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
            $studentExam->setExam($this);
        }

        return $this;
    }

    public function removeStudentExam(StudentExam $studentExam): self
    {
        if ($this->studentExams->contains($studentExam)) {
            $this->studentExams->removeElement($studentExam);
            // set the owning side to null (unless already changed)
            if ($studentExam->getExam() === $this) {
                $studentExam->setExam(null);
            }
        }

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

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

}
