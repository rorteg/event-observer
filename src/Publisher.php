<?php

namespace MadeiraMadeiraBr\Event;

use SplObserver;
use InvalidArgumentException;

class Publisher implements PublisherInterface
{
    /**
     * @var array
     */
    protected $linkedList = [];

    /**
     * @var array
     */
    protected $observers = [];

    /**
     * @var mixed
     */
    protected $event;

    /**
     * Attaches an SplObserver so that it can be notified of updates.
     *
     * @param SplObserver $observer
     * @param int $priority
     * @return void
     */
    public function attach(SplObserver $observer) : void
    {
        if (! ($observer instanceof ObserverInterface)) {
            throw new InvalidArgumentException(
                'The observer class needs to implement Application\Constants\Event\ObserverInterface'
            );
        }

        $observerKey = spl_object_hash($observer);
        $this->observers[$observerKey] = $observer;
        $this->linkedList[$observerKey] = $observer->getPriority();
        arsort($this->linkedList);
    }

    /**
     * Detaches an observer from the subject to no longer notify it of updates.
     *
     * @param SplObserver $observer
     * @return void
     */
    public function detach(SplObserver $observer) : void
    {
        if (! ($observer instanceof ObserverInterface)) {
            throw new InvalidArgumentException(
                'The observer class needs to implement Application\Constants\Event\ObserverInterface'
            );
        }

        $observerKey = spl_object_hash($observer);
        unset($this->observers[$observerKey]);
        unset($this->linkedList[$observerKey]);
    }

    /**
     * Notifies all attached observers.
     *
     * @return void
     */
    public function notify() : void
    {
        foreach ($this->linkedList as $key => $value) {
            $this->observers[$key]->update($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribers() : array
    {
        $observers = $this->observers;
        $observersSort = [];

        foreach ($this->linkedList as $key => $value) {
            $observersSort[] = $this->observers[$key];
        }

        return $observersSort;
    }

    /**
     * {@inheritdoc}
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * {@inheritdoc}
     */
    public function setEvent($event) : PublisherInterface
    {
        $this->event = $event;
        return $this;
    }
}