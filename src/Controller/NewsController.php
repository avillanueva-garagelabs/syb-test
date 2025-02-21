<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use dsarhoya\DSYFilesBundle\Services\DSYFilesService as FileService;

#[Route('/secured/news')]
class NewsController extends AbstractController
{
    private FileService $fileService;

    public function __construct(
        FileService $fileService
    ) {
        $this->fileService = $fileService;
    }
    #[Route('/', name: 'news_index', methods: ['GET'])]
    public function index(NewsRepository $newsRepository): Response
    {
        return $this->render('news/index.html.twig', [
            'news' => $newsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'news_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($news);
            $entityManager->flush();

            return $this->redirectToRoute('news_index');
        }

        return $this->render('news/new.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'news_show', methods: ['GET'])]
    public function show(NewsRepository $newsRepository, int $id): Response
    {
        $news = $newsRepository->find($id);

        if (!$news) {
            throw $this->createNotFoundException('Noticia no encontrada');
        }

        return $this->render('news/show.html.twig', [
            'news' => $news,
        ]);
    }

    #[Route('/{id}/edit', name: 'news_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NewsRepository $newsRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $news = $newsRepository->find($id);

        if (!$news) {
            throw $this->createNotFoundException('Noticia no encontrada');
        }

        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('news_index');
        }

        return $this->render('news/edit.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'news_delete', methods: ['POST'])]
    public function delete(Request $request, NewsRepository $newsRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $news = $newsRepository->find($id);

        if (!$news) {
            throw $this->createNotFoundException('Noticia no encontrada');
        }

        if ($news->getMainPhoto()) {
            $this->fileService->deleteAWSFile($news->getMainPhoto());
        }

        if ($this->isCsrfTokenValid('delete' . $news->getId(), $request->request->get('_token'))) {
            $entityManager->remove($news);
            $entityManager->flush();
        }

        return $this->redirectToRoute('news_index');
    }
}
