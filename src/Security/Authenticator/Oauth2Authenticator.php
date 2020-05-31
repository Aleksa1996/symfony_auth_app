<?php


namespace App\Security\Authenticator;


use App\Repository\Oauth2AccessTokenRepository;
use App\Service\Http\Request as RequestService;
use App\Service\Http\Response as ResponseService;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class Oauth2Authenticator extends AbstractGuardAuthenticator
{
    /**
     * @var ResourceServer
     */
    private $resourceServer;

    /**
     * @var Oauth2AccessTokenRepository
     */
    private $oauth2AccessTokenRepository;

    /**
     * @var RequestService
     */
    private $requestService;

    /**
     * @var ResponseService
     */
    private $responseService;

    /**
     * Oauth2Authenticator constructor.
     * @param ResourceServer $resourceServer
     * @param Oauth2AccessTokenRepository $oauth2AccessTokenRepository
     * @param RequestService $requestService
     * @param ResponseService $responseService
     */
    public function __construct(
        ResourceServer $resourceServer,
        Oauth2AccessTokenRepository $oauth2AccessTokenRepository,
        RequestService $requestService,
        ResponseService $responseService)
    {
        $this->resourceServer = $resourceServer;
        $this->oauth2AccessTokenRepository = $oauth2AccessTokenRepository;
        $this->requestService = $requestService;
        $this->responseService = $responseService;
    }

    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        return $request;
    }

    /**
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (null === $credentials) {
            // The token header was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
            return null;
        }

        /**
         * Here we used credentials as request
         */
        if ($credentials instanceof Request) {
            $credentials = $this->requestService->toPsr7($credentials);
        }

        $psrRequest = null;
        try {
            $psrRequest = $this->resourceServer->validateAuthenticatedRequest($credentials);
        } catch (OAuthServerException $e) {
            throw new CustomUserMessageAuthenticationException(
                sprintf('The resource server rejected the request. Hint: %s', $e->getHint())
            );
        }

        // if a User is returned, checkCredentials() is called
        $userId = $psrRequest->getAttribute('oauth_user_id');
        return $userProvider->loadUserById($userId);
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function supportsRememberMe()
    {
        return false;
    }
}