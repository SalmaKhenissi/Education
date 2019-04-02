<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParameterRepository")
 */
class Parameter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *  pattern="/[0-9]{8}/"
     * )
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *     message = "L'email '{{ value }}' n'est pas un email valide.",
     *     checkMX = true
     * )
     */
    private $email;

    


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $bilingDesc;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $extraDesc;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $helpDesc;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $programDesc;

    

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

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

    

   

    public function getbilingDesc(): ?string
    {
        return $this->bilingDesc;
    }

    public function setbilingDesc(string $bilingDesc): self
    {
        $this->bilingDesc = $bilingDesc;

        return $this;
    }

    public function getExtraDesc(): ?string
    {
        return $this->extraDesc;
    }

    public function setExtraDesc(string $extraDesc): self
    {
        $this->extraDesc = $extraDesc;

        return $this;
    }

    public function getHelpDesc(): ?string
    {
        return $this->helpDesc;
    }

    public function setHelpDesc(string $helpDesc): self
    {
        $this->helpDesc = $helpDesc;

        return $this;
    }

    public function getProgramDesc(): ?string
    {
        return $this->programDesc;
    }

    public function setProgramDesc(string $programDesc): self
    {
        $this->programDesc = $programDesc;

        return $this;
    }

   
}
