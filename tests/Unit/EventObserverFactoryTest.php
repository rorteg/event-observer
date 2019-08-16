<?php

namespace MadeiraMadeiraBr\Unit\Event\Tests;

use PHPUnit\Framework\TestCase;
use MadeiraMadeiraBr\Event\EventObserverFactory;
use MadeiraMadeiraBr\Event\ObserverInterface;

class EventObserverFactoryTest extends TestCase
{
    public function testGetInstance()
    {
        $factoryInstance = EventObserverFactory::getInstance();

        $this->assertInstanceOf(EventObserverFactory::class, $factoryInstance);
    }

    public function testAddEventKey()
    {
        $factoryInstance = EventObserverFactory::getInstance();
        $factoryInstance->addEventKey('event_test');
        $this->assertArrayHasKey('event_test', $factoryInstance->getEvents());
    }

    public function testAddObserversToEvent()
    {
        $factoryInstance = EventObserverFactory::getInstance();
        $factoryInstance->addObserversToEvent('event_test', [
            ObserverInterface::class
        ]);

        $this->assertContains(ObserverInterface::class, $factoryInstance->getEvents()['event_test']);
    }

    /**
     * @depends testAddObserversToEvent
     */
    public function testSingletonInstanceCyclePersistence()
    {
        $factoryInstance = EventObserverFactory::getInstance();
        $this->assertContains(ObserverInterface::class, $factoryInstance->getEvents()['event_test']);
    }

    /**
     * @expectedException \MadeiraMadeiraBr\Event\EventException
     */
    public function testAttachObserverReferenceToEventWhenDoNotExistsClass()
    {
        $factoryInstance = EventObserverFactory::getInstance();
        $factoryInstance->addObserversToEvent('event_test', [
            'ClassReferenceNotExists'
        ]);

        $factoryInstance->dispatchEvent('event_test');
    }
}