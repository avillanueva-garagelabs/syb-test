<?php

namespace App\EventSubscriber;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use App\Entity\News;
use dsarhoya\DSYFilesBundle\Services\DSYFilesService;
use JMS\Serializer\Metadata\StaticPropertyMetadata;

class NewsSerializationSubscriber implements EventSubscriberInterface
{
    private $fileService;

    public function __construct(DSYFilesService $fileService)
    {
        $this->fileService = $fileService;
    }

    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
                'class' => News::class,
            ],
        ];
    }

    public function onPostSerialize(ObjectEvent $event)
    {
        $entity = $event->getObject();
        /** @var JsonSerializationVisitor $visitor */
        $visitor = $event->getVisitor();

        if ($entity instanceof News) {
            if (null !== $entity->getMainPhoto()) {
                $this->addProperty($visitor, 'image_key_url', $this->fileService->fileUrl('news/' . $entity->getMainPhoto(), ['signed' => true]));
            }
        }
    }

    private function addProperty($visitor, $propertyName, $propertyValue)
    {
        $visitor->visitProperty(new StaticPropertyMetadata('', $propertyName, $propertyValue), $propertyValue);
    }
}
