<?php

namespace Server\Application\Core\Event\Tests;

use PHPUnit\Framework\TestCase;
use Server\Core\Event\Publisher;
use SplSubject;
use Server\Core\Event\ObserverInterface as Observer;

class PublisherTest extends TestCase
{
    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * @var Observer
     */
    private $observerStubClass;

    /**
     * {@inheritdoc}
     */
    public function setUp() : void
    {
        $this->observerStubClass = $this->getMockBuilder(Observer::class)
            ->setMethods(['update', 'getPriority'])
            ->getMock();
        
        $this->publisher = new Publisher();
    }

    public function testIfPHPSplSubjectInstance() : void
    {
        $this->assertInstanceOf(SplSubject::class, $this->publisher);
    }

    public function testAttachObserver() :  void
    {
        $this->publisher->attach($this->observerStubClass);

        $this->assertContains($this->observerStubClass, $this->publisher->getSubscribers());
    }

    /**
     * @depends testIfPHPSplSubjectInstance
     */
    public function testDetachObserver() : void
    {
        $this->publisher->detach($this->observerStubClass);

        $this->assertNotContains($this->observerStubClass, $this->publisher->getSubscribers());
    }

    public function testNotify()
    {
        $this->observerStubClass->expects($this->once())
            ->method('update')
            ->with($this->callback(function ($subject) {
                $subject->setEvent(['data' => 'test']);
                return true;
            }));

        $this->publisher->attach($this->observerStubClass);
        $this->publisher->notify();

        $this->assertContains('test', $this->publisher->getEvent());
    }

    public function testDataEventDataChangeAndPriority()
    {
        $originalData = ['data' => 'test1'];
        $expectedData = ['data' => 'test3'];

        /** @var Observer $observer1 */
        $observer1 = $this->getMockBuilder(Observer::class)
            ->setMethods(['update', 'getPriority'])
            ->getMock();
        
        $observer1->expects($this->once())
        ->method('update')
        ->with($this->callback(function ($subject) use ($originalData) {
            $subject->setEvent($originalData);
            return true;
        }));

        $observer1->expects($this->once())
            ->method('getPriority')
            ->willReturn(10);

        /** @var Observer $observer2 */
        $observer2 = $this->getMockBuilder(Observer::class)
            ->setMethods(['update', 'getPriority'])
            ->getMock();
        
        $observer2->expects($this->once())
        ->method('update')
        ->with($this->callback(function ($subject) use ($expectedData) {
            $subject->setEvent($expectedData);
            return true;
        }));

        $observer2->expects($this->once())
            ->method('getPriority')
            ->willReturn(5);

        $publisher = new Publisher('General');
        $publisher->attach($observer1);
        $publisher->attach($observer2);
        $publisher->notify();

        $this->assertSame($expectedData, $publisher->getEvent());
    }
}