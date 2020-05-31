<?php


namespace App\Controller;


use DateInterval;
use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\PasswordGrant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Nyholm\Psr7\Response as Psr7Response;
use Throwable;
use App\Service\Http\Request as RequestService;
use App\Service\Http\Response as ResponseService;


class AuthController extends AbstractController
{
    /**
     * @var AuthorizationServer
     */
    private $authorizationServer;

    /**
     * @var PasswordGrant
     */
    private $passwordGrant;

    /**
     * @var RequestService
     */
    private $requestService;

    /**
     * @var ResponseService
     */
    private $responseService;

    /**
     * AuthController constructor.
     *
     * @param AuthorizationServer $authorizationServer
     * @param PasswordGrant $passwordGrant
     * @param RequestService $requestService
     * @param ResponseService $responseService
     */
    public function __construct(
        AuthorizationServer $authorizationServer,
        PasswordGrant $passwordGrant,
        RequestService $requestService,
        ResponseService $responseService
    )
    {
        $this->authorizationServer = $authorizationServer;
        $this->passwordGrant = $passwordGrant;
        $this->requestService = $requestService;
        $this->responseService = $responseService;
    }

    /**
     * @Route("/auth/accessToken", name="api_get_access_token", methods={"POST"})
     * @param Request $request
     *
     * @return Response|StreamedResponse
     */
    public function getAccessToken(Request $request)
    {
        $this->passwordGrant->setRefreshTokenTTL(new DateInterval('P1M'));

        return $this->withErrorHandling(function () use ($request) {

            $this->passwordGrant->setRefreshTokenTTL(new DateInterval('P1M'));

            $this->authorizationServer->enableGrantType(
                $this->passwordGrant,
                new DateInterval('PT1H')
            );

            return $this->authorizationServer->respondToAccessTokenRequest(
                $this->requestService->toPsr7($request),
                new Psr7Response()
            );
        });
    }

    /**
     * Handle potential errors thrown by authorization server
     *
     * @param $callback
     *
     * @return Response|StreamedResponse
     */
    private function withErrorHandling($callback)
    {
        try {
            return $this->responseService->fromPsr7($callback());
        } catch (OAuthServerException $e) {
            return $this->responseService->fromPsr7($e->generateHttpResponse(new Psr7Response()));
        } catch (Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Throwable $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}