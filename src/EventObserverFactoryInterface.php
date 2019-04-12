<?php

namespace MadeiraMadeiraBr\Event;

interface EventObserverFactoryInterface
{
    /**
     * Get Factory Instance
     *
     * @return EventObserverFactoryInterface
     */
    public static function getInstance() : EventObserverFactoryInterface;

    /**
     * Add event key
     *
     * @param string $eventKey
     * @return EventObserverFactoryInterface
     */
    public function addEventKey(string $eventKey) : EventObserverFactoryInterface;

    /**
     * Get array with event keys and observables class references values.
     *
     * @return array
     */
    public function getEvents() : array;

    /**
     * Add observables to event key
     *
     * @param string $eventKey
     * @param array $observers
     * @return EventObserverFactoryInterface
     */
    public function addObserversToEvent(string $eventKey, array $observers) : EventObserverFactoryInterface;

    /**
     * Fire event
     *
     * @param stringt $eventKey
     * @param mixed $data
     * @return mixed
     */
    public function dispatchEvent(string $eventKey, $data = []) : PublisherInterface;

}
