<?php

namespace Backend\Database\Repository;

use Backend\Database\Db;

abstract class AbstractRepository
{
    /**
     * @var Db
     */
    protected $db;

    /**
     * @param Db $db
     */
    public function __construct(Db $db)
    {
        $this->db = $db;
    }
}