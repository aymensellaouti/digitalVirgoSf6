<?php

namespace App\EventSubscriber;

use App\Event\AddPersonEvent;
use App\services\MailerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersonSubscriber implements EventSubscriberInterface
{

    public function __construct(private MailerService $mailerService)
    {
    }

//    public function onPersonAdd($event): void
//    {
//        // ...
//    }

    public static function getSubscribedEvents(): array
    {
        return [
            AddPersonEvent::ADD_PRODUCT_EVENT => 'onPersonAdded',
        ];
    }

    public function onPersonAdded(AddPersonEvent $addPersonEvent): void {
        $person = $addPersonEvent->getPerson();
        $message = "La personne ".$person->getName()." a été ajouté avec succès";
        $template = "<h1> $message </h1>";
        $this->mailerService->sendEmail(template: $template);
    }
}
