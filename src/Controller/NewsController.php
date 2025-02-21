<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/news')]
class NewsController extends AbstractController
{
    #[Route('/', name: 'news_index', methods: ['GET'])]
    public function index(NewsRepository $newsRepository): Response
    {
        return $this->render('news/index.html.twig', [
            'news' => $newsRepository->findAll(),
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
}
