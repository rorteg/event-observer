<?php

namespace MadeiraMadeiraBr\Event;

use MadeiraMadeiraBr\Event\PublisherInterface;
use MadeiraMadeiraBr\Event\Publisher;
use MadeiraMadeiraBr\Event\EventObserverFactoryInterface;

final class EventObserverFactory implements EventObserverFactoryInterface
{
    /** @var PublisherInterface|null */
    private $publisher;

    /**
     * @var array
     */
    private $events = [];

    /**
     * @var EventObserverFactoryInterface
     */
    private static $instance;

    /**
     * {@inheritdoc}
     */
    public static function getInstance() : EventObserverFactoryInterface
    {
        if (! self::$instance) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    /**
     * {@inheritdoc}
     */
    public function addEventKey(string $eventKey) : EventObserverFactoryInterface
    {
        $this->events[$eventKey] = [];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEvents() : array
    {
        return $this->events;
    }

    /**
     * {@inheritdoc}
     */
    public function addObserversToEvent(string $eventKey, array $observers) : EventObserverFactoryInterface
    {
        $this->events[$eventKey] = $observers;
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws EventException
     */
    public function dispatchEvent(string $eventKey, $data = []) : PublisherInterface
    {
        if (! array_key_exists($eventKey, $this->events)) {
            throw new EventException('This event has not been set.');
        }

        $this->publisher = new Publisher();
        $this->publisher->setEvent($data);

        if (is_array($this->events[$eventKey])) {
            foreach ($this->events[$eventKey] as $observer) {
                $this->attachObserverReferenceToEvent($observer);
            }

            $this->publisher->notify();
        }
        
        return $this->publisher;
    }

    /**
     * Attach Observer Referente to Event.
     *
     * @param ObserverInterface $observerReference
     * @return void
     * @throws EventException
     */
    private function attachObserverReferenceToEvent($observerReference)
    {
        if (! class_exists($observerReference)) {
            throw new EventException(
                'The observer reference must be an existing PHP class and implement the ObserverInterface'
            );
        }

        $this->publisher->attach(new $observerReference);
    }
}
