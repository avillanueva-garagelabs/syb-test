<?php

namespace App\EventSubscriber;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use App\Entity\News;
use dsarhoya\DSYFilesBundle\Services\DSYFilesService;

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
        $news = $event->getObject();

        if ($news instanceof News) {
            $fileKey = $news->getFileKey();
            if ($fileKey) {
                $fileUrl = $this->fileService->getObjectUrl($fileKey);
                $news->setMainPhotoUrl($fileUrl);
            }
        }
    }
}
