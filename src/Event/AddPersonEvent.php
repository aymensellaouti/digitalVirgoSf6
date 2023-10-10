<?php

namespace App\Event;


use App\Entity\Person;
use Symfony\Contracts\EventDispatcher\Event;

class AddPersonEvent extends Event
{
    public function __construct(private Person $person)
    {
    }

    const ADD_PRODUCT_EVENT = 'person.added';

    /**
     * @return Person
     */
    public function getPerson(): Person
    {
        return $this->person;
    }

}