<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GuardianRepository")
 * @UniqueEntity("cin")
 */
class Guardian extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length( min = 3, max = 100)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length( min = 3, max = 100)
     */
    private $job;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Student", mappedBy="guardian" , cascade={"all"}, orphanRemoval=true)
     */
    private $children;

    /**
     * @ORM\Column(type="string")
     * @Assert\Regex(
     *  pattern="/[0-9]{8}/"
     * )
     */
    private $cin;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }
    public function __toString(){
        return $nom;
     }

   

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $job): self
    {
        $this->job = $job;

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
     * @return (Role|string)[]
     */
    public function getRoles()
    {
        return ['ROLE_GUARDIAN'];

    }

    /**
     * @return Collection|Student[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(Student $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setGuardian($this);
        }

        return $this;
    }

    public function removeChild(Student $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getGuardian() === $this) {
                $child->setGuardian(null);
            }
        }

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

    

   
}
