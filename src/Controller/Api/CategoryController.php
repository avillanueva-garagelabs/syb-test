<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Form\Api\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use JMS\Serializer\SerializerInterface;

#[Route("/categories")]
class CategoryController extends ApiBaseController
{
  private ValidatorInterface $validator;
  private ManagerRegistry $doctrine;

  public function __construct(ValidatorInterface $validator, ManagerRegistry $doctrine, SerializerInterface $serializer)
  {
    $this->validator = $validator;
    $this->doctrine = $doctrine;
    parent::__construct($serializer);
  }

  #[Route("", name: "api_category_list", methods: ["GET"])]
  public function list(CategoryRepository $categoryRepository): Response
  {
    $categories = $categoryRepository->findAll();
    return $this->serializedResponse($categories, ['category_list']);
  }

  #[Route("/{id}", name: "api_category_detail", methods: ["GET"])]
  public function detail(CategoryRepository $categoryRepository, int $id): Response
  {
    $category = $categoryRepository->find($id);
    return $this->serializedResponse($category, ['category_detail']);
  }

  #[Route("", name: "api_category_create", methods: ["POST"])]
  public function create(Request $request): Response
  {
    $category = new Category();
    $form = $this->createForm(CategoryType::class, $category);
    $form->submit(json_decode($request->getContent(), true));

    if (!$form->isValid()) {
      return $this->errorResponse((string) $form->getErrors(true, false), Response::HTTP_BAD_REQUEST);
    }

    $entityManager = $this->doctrine->getManager();
    $entityManager->persist($category);
    $entityManager->flush();

    return $this->serializedResponse($category, ['category_detail'], Response::HTTP_CREATED);
  }

  #[Route("/{id}", name: "api_category_update", methods: ["PUT"])]
  public function update(Request $request, CategoryRepository $categoryRepository, int $id): Response
  {
    $category = $categoryRepository->find($id);
    $form = $this->createForm(CategoryType::class, $category);
    $form->submit(json_decode($request->getContent(), true));

    if (!$form->isValid()) {
      return $this->errorResponse((string) $form->getErrors(true, false), Response::HTTP_BAD_REQUEST);
    }

    $this->doctrine->getManager()->flush();

    return $this->serializedResponse($category, ['category_detail']);
  }

  #[Route("/{id}", name: "api_category_delete", methods: ["DELETE"])]
  public function delete(CategoryRepository $categoryRepository, int $id): Response
  {
    $category = $categoryRepository->find($id);
    $entityManager = $this->doctrine->getManager();
    $entityManager->remove($category);
    $entityManager->flush();

    return new Response(null, Response::HTTP_NO_CONTENT);
  }

  protected function errorResponse($errors, int $statusCode): Response
  {
    $errorMessages = [];
    foreach ($errors as $error) {
      $errorMessages[] = $error->getMessage();
    }

    $data = [
      'errors' => $errorMessages,
    ];
    $json = $this->serializer->serialize($data, 'json');
    return new Response($json, $statusCode, ['Content-Type' => 'application/json']);
  }
}
