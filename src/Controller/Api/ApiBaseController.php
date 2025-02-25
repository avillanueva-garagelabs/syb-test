<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiBaseController extends AbstractController
{
  protected SerializerInterface $serializer;

  public function __construct(
    SerializerInterface $serializer,
  ) {
    $this->serializer = $serializer;
  }

  protected function serializedResponse($data, array $groups = [], int $statusCode = 200): Response
  {
    $context = SerializationContext::create()->setGroups($groups);
    $serializedData = $this->serializer->serialize($data, 'json', $context);

    return new JsonResponse($serializedData, $statusCode, [], true);
  }

  protected function errorResponse($errors, int $statusCode): Response
  {
    $errorMessages = [];
    if (is_array($errors)) {
      foreach ($errors as $error) {
        $errorMessages[] = $error->getMessage();
      }
    } else {
      $errorMessages = $errors;
    }

    $data = [
      'errors' => $errorMessages,
    ];
    $json = $this->serializer->serialize($data, 'json');
    return new Response($json, $statusCode, ['Content-Type' => 'application/json']);
  }
}
