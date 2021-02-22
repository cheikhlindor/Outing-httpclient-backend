<?php


namespace App\data;


use App\Entity\Campus;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;


class SearcheData
{
    /**
     * @var string
     */
    public $q = '';


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Campus")
     * @ORM\JoinColumn(nullable=false)
     */
    public $campus ;

    /**
     * @var date
     */
    public $dated;

    /**
     * @var date
     */
     public $datef;

     /**
     @var string
      */
      public $user ;
      /**
     @var string
      */
      public $user1 ;
      /**
     @var string
      */
      public $user2 ;
      /**
     @var string
      */
      public $user3 ;
}