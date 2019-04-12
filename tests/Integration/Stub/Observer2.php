<?php

namespace MadeiraMadeiraBr\Integration\Event\Tests\Stub;

use MadeiraMadeiraBr\Event\ObserverInterface;

class Observer2 implements ObserverInterface
{
    /**
     * {@inheritdoc}
     */
    public function update(\SplSubject $subject)
    {
        // Change original data for tests.
        $eventData = $subject->getEvent();
        $subject->setEvent(array_merge($eventData, ['value2' => 'Object (Observer2) Stub executed.']));
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority(): int
    {
        return 10;
    }
}