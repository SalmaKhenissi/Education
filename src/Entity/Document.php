<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 * @Vich\Uploadable
 */
class Document
{
    const TYPE = [
        0 => 'Cours',
        1 => 'Exercies',
        2 => 'TP',
        3 => 'Examen',
    ];

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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $postedAt;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Teacher", inversedBy="documents")
     */
    private $teacher;

    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $docName;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="files", fileNameProperty="docName")
     * 
     * @var File
     */
    private $docFile;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Section", inversedBy="documents")
     */
    private $sections;

    

    public function __construct()
    {
        $this->section = new ArrayCollection();
        $this->postedAt = new \DateTime('now');
        $this->sections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    

    /**
     * Set postedAt
     *
     * @param \DateTime $postedAt
     *
     * @return Document
     */
    public function setPostedAt($postedAt)
    {
        $this->postedAt = $postedAt;

        return $this;
    }

    /**
     * Get postedAt
     *
     * @return \DateTime
     */
    public function getPostedAt()
    {
        return $this->postedAt;
    }

    

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getDocName(): ?string
    {
        return $this->docName;
    }

    public function setDocName( $docName): self
    {
        $this->docName = $docName;

        return $this;
    }
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $docFile
     */
    public function setDocFile(?File $docFile = null): void
    {
        $this->docFile = $docFile;

    }

    public function getDocFile(): ?File
    {
        return $this->docFile;
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

   
}
