<?php

namespace Application\Core\Event\Tests;

use PHPUnit\Framework\TestCase;
use Application\Core\Event\Publisher;
use SplSubject;
use Application\Core\Event\Tests\Stub\Observer;

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
            ->setMethods(['update'])
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

    public function testPriorityExec()
    {
        /** @var Observer $observer1 */
        $observer1 = $this->getMockBuilder(Observer::class)
            ->setMethods(['update'])
            ->getMock();
    
        /** @var Observer $observer2 */
        $observer2 = $this->getMockBuilder(Observer::class)
            ->setMethods(['update'])
            ->getMock();

        /** @var Observer $observer3 */    
        $observer3 = $this->getMockBuilder(Observer::class)
            ->setMethods(['update'])
            ->getMock();

        $publisher = new Publisher();
        $publisher->attach($observer1, 10);
        $publisher->attach($observer2, 5);
        $publisher->attach($observer3, 0);
        $observers = $publisher->getSubscribers();

        $this->assertSame($observer3, current($observers));
    }
}