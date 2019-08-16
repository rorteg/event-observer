<?php

namespace MadeiraMadeiraBr\Integration\Event\Tests;

use PHPUnit\Framework\TestCase;
use MadeiraMadeiraBr\Event\EventObserverFactory;
use MadeiraMadeiraBr\Integration\Event\Tests\Stub\Observer;
use MadeiraMadeiraBr\Integration\Event\Tests\Stub\Observer2;

class EventObserverFactoryTest extends TestCase
{
    public function testDispatchEventFeature()
    {
        $eventFactory = EventObserverFactory::getInstance();
        $eventFactory->addEventKey('event_test');
        $eventFactory->addObserversToEvent('event_test', [Observer2::class, Observer::class]);

        $publisher = $eventFactory->dispatchEvent('event_test');

        $this->assertEquals('Object (Observer2) Stub executed.', \current($publisher->getEvent()));
    }

    public function testDispatchEventWhenEventNotExists()
    {
        $eventFactory = EventObserverFactory::getInstance();
        $publisher = $eventFactory->dispatchEvent('event_not_exists');
        $this->assertIsArray($publisher->getEvent());
    }
}