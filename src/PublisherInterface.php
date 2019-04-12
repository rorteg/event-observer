<?php

namespace MadeiraMadeiraBr\Event;

interface PublisherInterface
{
    /**
     * Get Observers subscribers
     *
     * @return array
     */
    public function getSubscribers() : array;

    /**
     * Get event state
     *
     * @return mixed
     */
    public function getEvent();

    /**
     * Set event state
     *
     * @param mixed $event
     * @return self
     */
    public function setEvent($event) : self;
}