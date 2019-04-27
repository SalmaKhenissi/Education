<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SchoolYearRepository")
 */
class SchoolYear
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
    private $libelle;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $finishAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Section", mappedBy="schoolYear")
     */
    private $sections;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quarter", mappedBy="schoolYear")
     */
    private $quarters;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
        $this->quarters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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
            $section->setSchoolYear($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->contains($section)) {
            $this->sections->removeElement($section);
            // set the owning side to null (unless already changed)
            if ($section->getSchoolYear() === $this) {
                $section->setSchoolYear(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Quarter[]
     */
    public function getQuarters(): Collection
    {
        return $this->quarters;
    }

    public function addQuarter(Quarter $quarter): self
    {
        if (!$this->quarters->contains($quarter)) {
            $this->quarters[] = $quarter;
            $quarter->setSchoolYear($this);
        }

        return $this;
    }

    public function removeQuarter(Quarter $quarter): self
    {
        if ($this->quarters->contains($quarter)) {
            $this->quarters->removeElement($quarter);
            // set the owning side to null (unless already changed)
            if ($quarter->getSchoolYear() === $this) {
                $quarter->setSchoolYear(null);
            }
        }

        return $this;
    }
}
