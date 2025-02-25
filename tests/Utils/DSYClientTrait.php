<?php

namespace App\Tests\Utils;

use App\Tests\Utils\DSYClient;
use Symfony\Bundle\FrameworkBundle\KernelBrowser as Client;

trait DSYClientTrait
{
  protected function dsyClient(array $options = []): DSYClient
  {
    $options = array_replace([
      'headers' => [],
    ], $options);

    /** @var Client */
    $client = $this->makeClient();

    foreach ($options['headers'] as $header => $value) {
      $client->setServerParameter("HTTP_{$header}", $value);
    }

    return new DSYClient($client);
  }

  protected function makeApiClient($apiKey, array $headers = []): DSYClient
  {
    $headers = array_merge([
      'api-key' => $apiKey,
    ], $headers);

    return $this->dsyClient([
      'headers' => $headers,
    ]);
  }

  protected function makeAnonymousClient(): DSYClient
  {
    return $this->dsyClient();
  }
}
