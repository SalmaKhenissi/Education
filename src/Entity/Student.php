<?php

namespace App\Entity;

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

   

   
}
