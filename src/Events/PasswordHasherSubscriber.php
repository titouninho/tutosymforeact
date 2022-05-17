<?php

namespace App\Events;

use App\Entity\User;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasherSubscriber implements EventSubscriberInterface{
    private $encode;

    public function __construct(UserPasswordHasherInterface $encode)
    {
        $this->encoder=$encode;
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['hashPassword', EventPriorities::PRE_WRITE]
        ];
    }

    public function hashPassword(ViewEvent $event){
        $user=$event->getControllerResult();
        dd($user);
        $method = $event->getRequest()->getMethod();

        if($user instanceof User && $method === "POST"){
            $hash = $this->encode->hashPassword($user,$user->getPassword());
            $user->setPassword($hash);
        } 
    }
} 