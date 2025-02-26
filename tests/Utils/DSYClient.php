<?php

namespace App\Tests\Utils;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class DSYClient
{
  private $client;

  public function __construct(KernelBrowser $client)
  {
    $this->client = $client;
  }

  public function postJson($url, $json, $headers = [])
  {
    return $this->client->request(
      'POST',
      $url,
      $json,
      [],
      array_merge($headers, [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_X_Requested-With' => 'XMLHttpRequest',
      ]),
      '',
    );
  }

  public function putJson($url, $json, $headers = [])
  {
    return $this->client->request(
      'PUT',
      $url,
      [],
      [],
      array_merge($headers, [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_X_Requested-With' => 'XMLHttpRequest',
      ]),
      $json
    );
  }

  public function delete($url, $headers = [])
  {
    return $this->client->request(
      'DELETE',
      $url,
      [],
      [],
      array_merge($headers, [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_X_Requested-With' => 'XMLHttpRequest',
      ])
    );
  }

  public function getJson($url, $query = [], $headers = [])
  {
    return $this->client->request(
      'GET',
      $url,
      $query,
      [],
      array_merge($headers, [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_X-Requested-With' => 'XMLHttpRequest',
      ])
    );
  }

  public function getResponse()
  {
    return $this->client->getResponse();
  }

  public function getResponseAsArray()
  {
    $response = $this->client->getResponse();
    return json_decode($response->getContent(), true);
  }

  public function getClient(): KernelBrowser
  {
    return $this->client;
  }
}
