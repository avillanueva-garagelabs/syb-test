<?php

namespace App\Service;

use App\Entity\News;
use dsarhoya\DSYFilesBundle\Services\DSYFilesService as FileService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class NewsImageService
{
  private FileService $fileService;
  private ParameterBagInterface $params;

  public function __construct(FileService $fileService, ParameterBagInterface $params)
  {
    $this->fileService = $fileService;
    $this->params = $params;
  }

  public function getNewsImageUrl(News $news): ?string
  {
    $mainPhoto = $news->getMainPhoto();

    if (!$mainPhoto) {
      return null;
    }

    if (str_starts_with($mainPhoto, 'news/')) {
      return $this->generateLocalUrl($mainPhoto);
    }

    try {
      return $this->fileService->getSignedUrl($mainPhoto);
    } catch (\Exception $e) {
      error_log("Error getting signed URL for news image: " . $e->getMessage());
      return null;
    }
  }

  private function generateLocalUrl(string $filePath): string
  {
    $baseUrl = $this->params->get('app.local_file_base_url');
    if (!$baseUrl) {
      throw new \RuntimeException('The "app.local_file_base_url" parameter must be defined.');
    }
    return $baseUrl . '/' . $filePath;
  }
}
