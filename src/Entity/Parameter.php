<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParameterRepository")
 * @Vich\Uploadable
 */
class Parameter
{
    const NUMBER = [
        1 => '1',
        2 => '2' ,
        3 => '3',
        
    ];

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
    private $schoolYear;

    

     /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $quarter;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slider1;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="images", fileNameProperty="slider1")
     * 
     * @var File
     */
    private $slider1File;

   

   
    /**
     * @ORM\Column(type="string", length=255)
     */

    private $slider3;
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="images", fileNameProperty="slider3")
     * 
     * @var File
     */
    private $slider3File;

    /**
     * @ORM\Column(type="string", length=255)
     */

    private $slider2;
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="images", fileNameProperty="slider2")
     * 
     * @var File
     */
    private $slider2File;

    public function __construct(){
        $this->updatedAt = new \DateTime('now');
    }

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

    

    public function getSchoolYear(): ?string
    {
        return $this->schoolYear;
    }

    public function setSchoolYear(string $schoolYear): self
    {
        $this->schoolYear = $schoolYear;

        return $this;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Paremeter
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getQuarter(): ?string
    {
        return $this->quarter;
    }

    public function getQuarterType(): string
    {
        return self::NUMBER[$this->quarter];
    }

    public function setQuarter(string $quarter): self
    {
        $this->quarter = $quarter;

        return $this;
    }

    public function getSlider1(): ?string
    {
        return $this->slider1;
    }

    public function setSlider1(string $slider1): self
    {
        $this->slider1 = $slider1;

        return $this;
    }

     /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $slider1File
     */
    public function setSlider1File(?File $slider1File = null): void
    {
        $this->slider1File = $slider1File;

    }

    public function getSlider1File(): ?File
    {
        return $this->slider1File;
    }


     

    public function getSlider3(): ?string
    {
        return $this->slider3;
    }

    public function setSlider3(string $slider3): self
    {
        $this->slider3 = $slider3;

        return $this;
    }

     /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $slider3File
     */
    public function setSlider3File(?File $slider3File = null): void
    {
        $this->slider3File = $slider3File;

    }

    public function getSlider3File(): ?File
    {
        return $this->slider3File;
    }


    public function getSlider2(): ?string
    {
        return $this->slider2;
    }

    public function setSlider2(string $slider2): self
    {
        $this->slider2 = $slider2;

        return $this;
    }

     /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $slider2File
     */
    public function setSlider2File(?File $slider2File = null): void
    {
        $this->slider2File = $slider2File;

    }

    public function getSlider2File(): ?File
    {
        return $this->slider2File;
    }

   
}
