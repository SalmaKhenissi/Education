<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Section;
use App\Entity\Quarter;
class ExamSearch{
 
    

    
    private $section;
    private $quarter;

    
    public function getSection()
    {
        return $this->section;
    }

    public function setSection(Section $section)
    {
         $this->section = $section;
         return $this;
    }

    public function getQuarter()
    {
        return $this->quarter;
    }

    public function setQuarter(Quarter $quarter)
    {
         $this->quarter = $quarter;
         return $this;
    }

    
     

    

}