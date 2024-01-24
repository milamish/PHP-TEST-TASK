<?php

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use League\OAuth2\Client\Provider\GenericProvider;
use PHPUnit\Framework\TestCase;

class intergrationTest extends TestCase
{
    private $httpClientMock;
    private $providerMock;

    protected function setUp(): void
    {
        // a mock for Guzzle HTTP client
        $this->httpClientMock = $this->createMock(Client::class);

        // a mock for OAuth 2.0 provider
        $this->providerMock = $this->createMock(GenericProvider::class);
    }

    public function testGitHubApiIntegration()
    {
        // OAuth App credentials and API details
        $client_id = 'github_client_id';
        $client_secret = 'github_client_secret';
        $redirect_uri = 'redirect_uri';
        $api_url = 'https://api.github.com';

        // OAuth 2.0 provider mock
        $this->providerMock->expects($this->once())
            ->method('getAuthorizationUrl')
            ->willReturn('https://github.com/login/oauth/authorize');

        // Guzzle HTTP client mock
        $responseBody = '{"login": "testuser"}';
        $this->httpClientMock->expects($this->once())
            ->method('get')
            ->willReturn(new Response(200, [], $responseBody));

        // OAuth 2.0 provider mock for access token
        $this->providerMock->expects($this->once())
            ->method('getAccessToken')
            ->with('authorization_code', ['code' => 'testcode'])
            ->willReturn($this->createMockAccessToken());

        // Running script logic
        $_GET['code'] = 'test_code';
        ob_start();
        include 'intergration.php';
        $output = ob_get_clean();

        // assertion based on the expected API response
        $this->assertStringContainsString('{"login": "testuser"}', $output);
    }

    private function createMockAccessToken()
    {
        // a mock of the AccessToken class from League\OAuth2\Client\Token
        return $this->createMock(\League\OAuth2\Client\Token\AccessToken::class);
    }
}

