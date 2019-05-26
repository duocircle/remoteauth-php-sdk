<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as GuzzleClient;
use RemoteAuthPhp\HttpClient;
use Psr\SimpleCache\CacheInterface;
use Tests\Mock\RemoteAuthUserMock;
use Tests\Mock\CacheMock;

class HttpClientTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->user = new RemoteAuthUserMock();

        $this->mockBuilder = $this->getMockBuilder(HttpClient::class);
        
        $this->mockCacheBuilder = $this->getMockBuilder(CacheMock::class);

        $this->mockHttpBuilder = $this->getMockBuilder(GuzzleClient::class);
    }

    /** @test */
    public function itRunsTests()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function itCachesGetRequests()
    {
        // Given
        $mockHttp = $this->mockHttpBuilder
            ->setMethods(['request'])
            ->getMock();

        $mockCache = $this->mockCacheBuilder
            ->setMethods(['has', 'set'])
            ->getMock();

        $mockClient = $this->mockBuilder
            ->setConstructorArgs([[], $mockCache, $mockHttp])
            ->setMethods(null)
            ->getMock();

        $cacheKey = $mockClient->getCacheKey($this->user, 'GET', '/api/one');
        $mockCache->expects($this->once())->method('has')->with($cacheKey);
        $mockCache->expects($this->once())->method('set')->with($cacheKey, null);

        // When
        $mockClient->request('GET', '/api/one', $this->user);
    }

    /** @test */
    public function itRetrievesResponsesFromCache()
    {
        // Given
        $mockHttp = $this->mockHttpBuilder->getMock();

        $mockCache = $this->mockCacheBuilder
            ->setMethods(['has', 'get'])
            ->getMock();

        $mockClient = $this->mockBuilder
            ->setConstructorArgs([[], $mockCache, $mockHttp])
            ->setMethods(null)
            ->getMock();

        $cacheKey = $mockClient->getCacheKey($this->user, 'GET', '/api/one');
        $mockCache->expects($this->once())->method('has')->with($cacheKey)->willReturn(true);
        $mockCache->expects($this->once())->method('get')->with($cacheKey);

        // When
        $mockClient->request('GET', '/api/one', $this->user);
    }

    /** @test */
    public function itAddsRequestHeaders()
    {
        // Given
        $mockHttp = $this->mockHttpBuilder
            ->setMethods(['request'])
            ->getMock();

        $mockCache = $this->mockCacheBuilder->getMock();

        $mockClient = $this->mockBuilder
            ->setConstructorArgs([[], $mockCache, $mockHttp])
            ->setMethods(null)
            ->getMock();

        $mockHttp->expects($this->once())
            ->method('request')
            ->with('GET', '/api/one', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->user->accessToken()
                ],
                'json' => null
            ]);

        // When
        $mockClient->request('GET', '/api/one', $this->user);
    }

    /** @test */
    public function itCallsGetRequests()
    {
        $mock = $this->mockBuilder->setMethods(['request'])->getMock();
        $mock->expects($this->once())
            ->method('request')
            ->with('GET', '/api/one', $this->user, null);
        
        // When
        $mock->get('/api/one', $this->user);
    }

    /** @test */
    public function itCallsPostRequests()
    {
        $mock = $this->mockBuilder->setMethods(['request'])->getMock();
        $mock->expects($this->once())
            ->method('request')
            ->with('POST', '/api/one', $this->user, [
                'payload' => 'value'
            ]);
        
        // When
        $mock->post('/api/one', $this->user, [
            'payload' => 'value'
        ]);
    }

    /** @test */
    public function itCallsPutRequests()
    {
        $mock = $this->mockBuilder->setMethods(['request'])->getMock();
        $mock->expects($this->once())
            ->method('request')
            ->with('PUT', '/api/one', $this->user, [
                'payload' => 'value'
            ]);
        
        // When
        $mock->put('/api/one', $this->user, [
            'payload' => 'value'
        ]);
    }

    /** @test */
    public function itCallsDeleteRequests()
    {
        $mock = $this->mockBuilder->setMethods(['request'])->getMock();
        $mock->expects($this->once())
            ->method('request')
            ->with('DELETE', '/api/one', $this->user, [
                'payload' => 'value'
            ]);
        
        // When
        $mock->delete('/api/one', $this->user, [
            'payload' => 'value'
        ]);
    }
}
