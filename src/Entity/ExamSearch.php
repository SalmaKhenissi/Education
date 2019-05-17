<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Quarter;
class ExamSearch{
 
    

    private $quarter;

    

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