<?php
namespace App\Data;

use App\Entity\Livre;

class SearchData
{

    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Categorie[]
     */
    public $nomCategorie = [];

    

    

}