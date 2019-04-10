<?php

namespace MadeiraMadeiraBr\Event;

interface ObserverInterface extends \SplObserver
{
    /**
     * Get execution priority
     *
     * @return integer
     */
    public function getPriority() : int;
}