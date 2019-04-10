<?php

namespace MadeiraMadeiraBr\Event;

interface EventObserverFactoryInterface
{
    /**
     * Fire event with subscribers (observers)
     *
     * @param stringt $eventName
     * @param array $subscribers array with ["int priority execution" => "Observer class reference"]
     * @param mixed $data
     * @return mixed
     */
    public static function dispatchEvent(string $eventName, array $subscribers, $data) : PublisherInterface;

    /**
     * @param array $observers
     * @return void
     */
    public static function attachObservers(array $observers) : void;

    /**
     * @param PublisherInterface $publisher
     * @return void
     */
    public static function setPublisher(PublisherInterface $publisher) : void;

    /**
     * @return PublisherInterface
     */
    public static function getPublisher() : PublisherInterface;
}