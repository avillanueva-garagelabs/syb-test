<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(NewsRepository $newsRepository): Response
    {
        // Obtener las últimas 10 noticias habilitadas, ordenadas por fecha de publicación descendente
        $latestNews = $newsRepository->findBy(
            ['enabled' => true], // Filtro: solo noticias habilitadas
            ['publicationDate' => 'DESC'],
            10 // Límite: 10 noticias
        );

        return $this->render('home/index.html.twig', [
            'latestNews' => $latestNews,
        ]);
    }
}
