<?php

/**
 * This file is part of the MusicBrainz API Wrapper created by Stephan Strate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package MusicBrainz\HttpClient
 * @author Stephan Strate <hello@stephan.codes>
 * @link https://github.com/stephan-strate/php-music-brainz-api
 * @copyright (c) 2021, Stephan Strate
 * @version 0.0.1
 */

namespace MusicBrainz\HttpClient;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClientFactory;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Compose customized http client from plugins and default headers.
 * @package MusicBrainz\HttpClient
 */
class Builder
{
    /**
     * Object that sends the http messages.
     * @var ClientInterface
     */
    private ClientInterface $httpClient;

    /**
     * Http client composition with plugins.
     * @var HttpMethodsClientInterface
     */
    private HttpMethodsClientInterface $pluginClient;

    /**
     * Factory to create requests. Used in {@link \MusicBrainz\HttpClient\Builder::$pluginClient}.
     * @var RequestFactoryInterface
     */
    private RequestFactoryInterface $requestFactory;

    /**
     * Factory to create streams. Used in {@link \MusicBrainz\HttpClient\Builder::$pluginClient}.
     * @var StreamFactoryInterface
     */
    private StreamFactoryInterface $streamFactory;

    /**
     * True if we should create a new {@link \MusicBrainz\HttpClient\Builder::$pluginClient} at next request.
     * @var bool
     */
    private bool $httpClientModified = true;

    /**
     * List of plugins that are already added or will be added to
     * {@link \MusicBrainz\HttpClient\Builder::$pluginClient}.
     * @var array<Plugin>
     */
    private array $plugins = [];

    /**
     * Default http headers.
     * @var array<string, string>
     */
    private array $headers = [];

    /**
     * Use custom properties or find installed client/factories.
     * @param ClientInterface|null          $httpClient
     * @param RequestFactoryInterface|null $requestFactory
     * @param StreamFactoryInterface|null  $streamFactory
     */
    public function __construct(
        ClientInterface $httpClient = null,
        RequestFactoryInterface  $requestFactory = null,
        StreamFactoryInterface $streamFactory = null
    ) {
        $this->httpClient = $httpClient ?: Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?: Psr17FactoryDiscovery::findStreamFactory();
    }

    /**
     * Returns http client with plugins. The client is updated automatically,
     * when retrieving a new one.
     * @return HttpMethodsClientInterface
     */
    public function getHttpClient(): HttpMethodsClientInterface
    {
        if ($this->httpClientModified) {
            $this->httpClientModified = false;

            $this->pluginClient = new HttpMethodsClient(
                (new PluginClientFactory())->createClient($this->httpClient, $this->plugins),
                $this->requestFactory,
                $this->streamFactory
            );
        }

        return $this->pluginClient;
    }

    /**
     * Add a new plugin to the end of the plugins chain.
     * @param Plugin $plugin    plugin to add
     */
    public function addPlugin(Plugin $plugin): void
    {
        $this->plugins[] = $plugin;
        $this->httpClientModified = true;
    }

    /**
     * Remove a plugin by its fully qualified class name (FQCN).
     * @param string $fqcn  fully qualified class name
     */
    public function removePlugin(string $fqcn): void
    {
        foreach ($this->plugins as $idx => $plugin) {
            if ($plugin instanceof $fqcn) {
                unset($this->plugins[$idx]);
                $this->httpClientModified = true;
            }
        }
    }

    /**
     * Add default http headers.
     * @param array<string, string> $headers
     */
    public function addHeaders(array $headers): void
    {
        $this->headers = array_merge($this->headers, $headers);

        $this->removePlugin(Plugin\HeaderAppendPlugin::class);
        $this->addPlugin(new Plugin\HeaderAppendPlugin($this->headers));
    }
}
