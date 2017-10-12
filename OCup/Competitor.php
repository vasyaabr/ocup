<?php

namespace OCup;


class Competitor
{
    public $name;
    public $surName;
    public $group;
    public $time;

    public function __construct($record)
    {
        $this->surName = trim($record[0]);
        $this->name = trim($record[1]);
        $this->group = trim($record[2]);
        $this->time = trim($record[3]);
    }

    public function fullName() : string
    {
        return ucfirst(strtolower(trim($this->surName))) . ' ' . ucfirst(strtolower(trim($this->name)));
    }
}