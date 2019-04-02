<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SectionRepository")
 */
class Section
{
    const LEVEL= [0 => '7' , 1 => '8' , 2 => '9' , 3 => '1' , 4 => '2' ,5 => '3' ,6 => '4'];
    const TRACK= [ 0 => 'Base' , 1 => 'Secondaire' , 2 => 'Sciences' , 3 => 'Economies' ,4 => 'Informatique' ,5 => 'Lettre' ,
                   6 => 'Sciences Expérimental', 7 => 'Techniques' , 8 => 'Mathématiques'];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="integer")
     * @Assert\Type("integer")
     */
    private $level;

     /**
     * @ORM\Column(type="integer")
     * @Assert\Type("integer")
     */
    private $nbrGroup;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Teacher", mappedBy="sections")
     */
    private $teachers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Student", mappedBy="section")
     */
    private $students;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seance", mappedBy="section")
     */
    private $seances;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $track;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $schoolYear;

    


    public function __construct()
    {
        $this->teachers = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->seances = new ArrayCollection();
    }

    public function __toString(){
        $l=$this->level;
        $g=$this->nbrGroup;
        $t=$this->track;
        return ($l.$t.$g);
     }

    public function getId(): ?int
    {
        return $this->id;
    }
    

    public function getNbrGroup(): ?int
    {
        return $this->nbrGroup;
    }

    public function setNbrGroup(int $nbrGroup): self
    {
        $this->nbrGroup = $nbrGroup;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }
    public function getLevelType(): int
    {
        return self::LEVEL[$this->level];
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Collection|Teacher[]
     */
    public function getTeachers(): Collection
    {
        return $this->teachers;
    }

    public function addTeacher(Teacher $teacher): self
    {
        if (!$this->teachers->contains($teacher)) {
            $this->teachers[] = $teacher;
            $teacher->addSection($this);
        }

        return $this;
    }

    public function removeTeacher(Teacher $teacher): self
    {
        if ($this->teachers->contains($teacher)) {
            $this->teachers->removeElement($teacher);
            $teacher->removeSection($this);
        }

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
            $student->setSection($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->contains($student)) {
            $this->students->removeElement($student);
            // set the owning side to null (unless already changed)
            if ($student->getSection() === $this) {
                $student->setSection(null);
            }
        }

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
            $seance->setSection($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): self
    {
        if ($this->seances->contains($seance)) {
            $this->seances->removeElement($seance);
            // set the owning side to null (unless already changed)
            if ($seance->getSection() === $this) {
                $seance->setSection(null);
            }
        }

        return $this;
    }

    public function getTrack(): ?string
    {
        return $this->track;
    }
    public function getTrackType(): string
    {
        return self::TRACK[$this->track];
    }

    public function setTrack(string $track): self
    {
        $this->track = $track;

        return $this;
    }

    public function getSchoolYear(): ?string
    {
        return $this->schoolYear;
    }

    public function setSchoolYear(string $schoolYear): self
    {
        $this->schoolYear = $schoolYear;

        return $this;
    }

    

   

   

   

   
}
