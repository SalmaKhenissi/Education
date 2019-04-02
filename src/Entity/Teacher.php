<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeacherRepository")
 * @UniqueEntity("cin")
 */
class Teacher extends User
{

    const TYPE= [
        0 => 'Recruté' ,
        1 => 'Vacataire',
    ];


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length( min = 3, max = 100)
     */
    private $subject;



    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Section", inversedBy="teachers")
     */
    private $sections;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *     message = "L'email '{{ value }}' n'est pas un email valide.",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     * @Assert\Regex(
     *  pattern="/[0-9]{8}/"
     * )
     */
    private $tel;

    

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seance", mappedBy="teacher")
     */
    private $seances;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string")
     * @Assert\Regex(
     *  pattern="/[0-9]{8}/"
     * )
     */
    private $cin;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
        $this->seances = new ArrayCollection();
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

   

    /**
     * @return (Role|string)[]
     */
    public function getRoles()
    {
        return ['ROLE_TEACHER'];
 
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): self
    {
        $this->tel = $tel;

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
            $seance->setTeacher($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): self
    {
        if ($this->seances->contains($seance)) {
            $this->seances->removeElement($seance);
            // set the owning side to null (unless already changed)
            if ($seance->getTeacher() === $this) {
                $seance->setTeacher(null);
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

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

   

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }    

    

     
}
