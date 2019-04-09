<?php

namespace Server\Core\Event;

use Server\Core\Event\PublisherInterface;
use Server\Core\Event\Publisher;
use Server\Core\Event\EventObserverFactoryInterface;

final class EventObserverFactory implements EventObserverFactoryInterface
{
    /** @var PublisherInterface|null */
    protected static $publisher = null;

    /**
     * {@inheritdoc}
     */
    public static function attachObservers(array $observers) : void
    {
        if (is_null(self::$publisher)) {
            self::setPublisher(new Publisher());
        }

        foreach ($observers as $priority => $observer) {
            self::$publisher->attach(new $observer($priority));
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function dispatchEvent(string $eventName, array $subscribers, $data = []) : PublisherInterface
    {
        self::attachObservers($subscribers);
        self::$publisher->setEvent($data);
        self::$publisher->notify();

        return self::$publisher;
    }

    /**
     * Set Publisher
     *
     * @param PublisherInterface $publisher
     * @return void
     */
    public static function setPublisher(PublisherInterface $publisher) : void
    {
        self::$publisher = $publisher;
    }

    /**
     * Get Publisher
     *
     * @return PublisherInterface
     */
    public static function getPublisher() : PublisherInterface
    {
        return self::$publisher;
    }
}
