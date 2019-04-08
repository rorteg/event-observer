<?php

namespace Application\Core\Event\Tests\Stub;

use SplSubject;
use Application\Core\Event\ObserverInterface;

class Observer implements ObserverInterface
{
    /**
     * his method is called when any SplSubject to which the observer is attached calls SplSubject::notify().
     *
     * @param SplSubject $subject
     * @return void
     */
    public function update(SplSubject $subject) : void
    {
        
    }
}