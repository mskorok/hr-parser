<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 17.11.20
 * Time: 12:55
 */

namespace App\Parser\Base;


use App\Interfaces\CrawleraHttpClientInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class HttpClientAdapter implements HttpClientInterface, CrawleraHttpClientInterface
{
    protected $httpClient;

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param $url
     * @param array $params
     * @return null|string
     * @throws TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    public function get($url, $params = []): string
    {
        if ($this->httpClient instanceof HttpClient) {
            $client = HttpClient::create();
            if (count($params) > 0) {
                $url .= '?' . http_build_query($params);
            }
            return $client->request('GET', $url)->getContent();

        }
        throw new \RuntimeException('HTTP Client not found');
    }

    /**
     * Requests an HTTP resource.
     *
     * Responses MUST be lazy, but their status code MUST be
     * checked even if none of their public methods are called.
     *
     * Implementations are not required to support all options described above; they can also
     * support more custom options; but in any case, they MUST throw a TransportExceptionInterface
     * when an unsupported option is passed.
     *
     * @param string $method
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws TransportExceptionInterface When an unsupported option is passed
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        if ($this->httpClient instanceof HttpClient) {
            $client = HttpClient::create();
            if (count($options) > 0) {
                $url .= '?' . http_build_query($options);
            }
            return $client->request('GET', $url);

        }
        throw new \RuntimeException('HTTP Client not found');
    }

    /**
     * Yields responses chunk by chunk as they complete.
     *
     * @param ResponseInterface|ResponseInterface[]|iterable $responses One or more responses created by the current HTTP client
     * @param float|null $timeout The idle timeout before yielding timeout chunks
     * @return ResponseStreamInterface
     */
    public function stream($responses, float $timeout = null): ResponseStreamInterface
    {
        if ($this->httpClient instanceof HttpClient) {
            $client = HttpClient::create();

            return $client->stream($responses, $timeout);

        }
        throw new \RuntimeException('HTTP Client not found');
    }
}