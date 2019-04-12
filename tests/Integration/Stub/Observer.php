<?php

namespace MadeiraMadeiraBr\Integration\Event\Tests\Stub;

use MadeiraMadeiraBr\Event\ObserverInterface;

class Observer implements ObserverInterface
{
    /**
     * {@inheritdoc}
     */
    public function update(\SplSubject $subject)
    {
        // Change original data for tests.
        $eventData = $subject->getEvent();
        $subject->setEvent(array_merge($eventData, ['value' => 'Object Stub executed.']));
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority(): int
    {
        return 2;
    }
}