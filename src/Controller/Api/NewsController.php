<?php

namespace App\Controller\Api;

use App\Entity\News;
use App\Form\Api\NewsType;
use App\Repository\NewsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use dsarhoya\DSYFilesBundle\Services\DSYFilesService as FileService;
use JMS\Serializer\SerializerInterface;

#[Route("/news")]
class NewsController extends ApiBaseController
{
  private ManagerRegistry $doctrine;
  private FileService $fileService;

  public function __construct(
    ManagerRegistry $doctrine,
    FileService $fileService,
    SerializerInterface $serializer
  ) {
    $this->doctrine = $doctrine;
    $this->fileService = $fileService;
    parent::__construct($serializer);
  }

  #[Route("", name: "api_news_list", methods: ["GET"])]
  public function list(NewsRepository $newsRepository): Response
  {
    $news = $newsRepository->findBy(['enabled' => true], ['publicationDate' => 'DESC']);
    return $this->serializedResponse($news, ['news_list']);
  }

  #[Route("/{id}", name: "api_news_detail", methods: ["GET"])]
  public function detail(NewsRepository $newsRepository, int $id): Response
  {
    $news = $newsRepository->find($id);

    if (!$news) {
      throw $this->createNotFoundException('Noticia no encontrada');
    }

    return $this->serializedResponse($news, ['news_detail']);
  }

  #[Route("", name: "api_news_create", methods: ["POST"])]
  public function create(Request $request): Response
  {
    $news = new News();
    $form = $this->createForm(NewsType::class, $news);
    $form->submit(array_merge($request->request->all(), $request->files->all()));

    if (!$form->isValid()) {
      return $this->errorResponse((string) $form->getErrors(true, false), Response::HTTP_BAD_REQUEST);
    }

    $file = $request->files->get('file');

    if ($file) {
      $news->setFile($file);
      $fileKey = sprintf('%s/%s', $news->getFilePath(), uniqid() . '.' . $news->getFile()->guessExtension());
      $this->fileService->AWSFileWithFileAndKey($news->getFile(), $fileKey, $news->getFileProperties());
      $news->setMainPhoto($fileKey);
    }

    $entityManager = $this->doctrine->getManager();
    $entityManager->persist($news);
    $entityManager->flush();

    return $this->serializedResponse($news, ['news_detail'], Response::HTTP_CREATED);
  }

  #[Route("/{id}", name: "api_news_update", methods: ["PUT"])]
  public function update(Request $request, NewsRepository $newsRepository, int $id): Response
  {
    $news = $newsRepository->find($id);
    $form = $this->createForm(NewsType::class, $news);
    $form->submit(json_decode($request->getContent(), true));

    if (!$form->isValid()) {
      return $this->errorResponse((string) $form->getErrors(true, false), Response::HTTP_BAD_REQUEST);
    }

    if ($news->getFile()) {
      // Eliminar el archivo anterior de S3
      if ($news->getMainPhoto()) {
        $this->fileService->deleteAWSFile($news->getMainPhoto());
      }

      // Subir el nuevo archivo a S3
      $fileKey = sprintf('%s/%s', $news->getFilePath(), uniqid() . '.' . $news->getFile()->guessExtension());
      $this->fileService->AWSFileWithFileAndKey($news->getFile(), $fileKey, $news->getFileProperties());
      $news->setMainPhoto($fileKey);
    }

    $this->doctrine->getManager()->flush();

    return $this->serializedResponse($news, ['news_detail']);
  }

  #[Route("/{id}", name: "api_news_delete", methods: ["DELETE"])]
  public function delete(NewsRepository $newsRepository, int $id): Response
  {
    $news = $newsRepository->find($id);
    if ($news->getMainPhoto()) {
      $this->fileService->deleteAWSFile($news->getMainPhoto());
    }

    $entityManager = $this->doctrine->getManager();
    $entityManager->remove($news);
    $entityManager->flush();

    return new Response(null, Response::HTTP_NO_CONTENT);
  }
}
