<?php

namespace App\Data;

use App\Entity\Author;
use App\Entity\Category;

class SearchData
{
    /**
     * @var string
     */
    public $reference = '';

    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var int
     */
    public $limit =9;

    /**
     * @var Category[]
     */
    public $categories = [];

    /**
     * @var null|Author
     */
    public $author;


}