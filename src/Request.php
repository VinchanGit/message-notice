<?php

declare(strict_types=1);

namespace MessageNotice;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Hyperf\Guzzle\ClientFactory;

class Request
{
    public array $query = [];

    public array $param = [];

    public array $json = [];

    public array $header = [];

    public string $domain = '';

    public string $route = '';

    public ClientFactory $clientFactory;

    public function __construct(ClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    public function post(): string
    {
        try {
            $client = $this->clientFactory->create([
                'base_uri' => $this->domain,
            ]);

            $content = $client->request('POST', $this->route, [
                RequestOptions::FORM_PARAMS => $this->param,
                RequestOptions::HEADERS => $this->header,
                RequestOptions::JSON => $this->json,
            ]);

            return $content->getBody()->getContents();
        } catch (RequestException|GuzzleException $exception) {
            return $exception->getMessage();
        }
    }

    public function get(): string
    {
        try {
            $client = $this->clientFactory->create([
                'base_uri' => $this->domain,
            ]);

            $content = $client->request('GET', $this->route, [
                RequestOptions::QUERY => $this->query,
                RequestOptions::HEADERS => $this->header,
            ]);

            return $content->getBody()->getContents();
        } catch (RequestException|GuzzleException $exception) {
            return $exception->getMessage();
        }
    }
}
