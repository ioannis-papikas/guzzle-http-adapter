<?php

/*
 * This file is part of the Guzzle Http Client Adapter.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HttpAdapter;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class HttpClient
 * @package HttpAdapter
 */
class HttpClient
{
    /**
     * @var Client
     */
    private $guzzleClient;

    /**
     * HttpClient constructor.
     */
    public function __construct()
    {
        $this->guzzleClient = new Client();
    }

    /**
     * @param string              $method
     * @param string|UriInterface $uri
     * @param array               $headers
     * @param array               $parameters
     * @param array               $multipart
     * @param string              $version
     *
     * @return mixed|ResponseInterface
     */
    public function send($method, $uri, array $headers = [], $parameters = [], $multipart = [], $version = '1.1')
    {
        // Create request
        $request = new Request($method, $uri, $headers, null, $version);

        // Set options
        $options = [];
        if (in_array(strtolower($method), ['post', 'put', 'patch'])) {
            if (!empty($multipart)) {
                foreach ($parameters as $name => $value) {
                    $multipart[] = ['name' => $name, 'contents' => $value];
                }
                $options['multipart'] = $multipart;
            } else {
                $options['form_params'] = $parameters;
            }
        } else if (!empty($parameters)) {
            $options['query'] = $parameters;
        }

        // Send request
        return $this->guzzleClient->send($request, $options);
    }
}
