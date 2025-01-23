<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends AbstractController {
  public function index(NewsRepository $newsRepository): Response {
    $latestNews = $newsRepository->findPublishedNews();

    return $this->render('news/index.html.twig', [
      'latestNews' => $latestNews,
    ]);
  }
}
