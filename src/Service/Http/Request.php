<?php


namespace App\Service\Http;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request
{
    /**
     * Converts symfony request to psr7 request
     *
     * @param SymfonyRequest $request
     *
     * @return ServerRequestInterface
     */
    public function toPsr7(SymfonyRequest $request)
    {
        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

        return $psrHttpFactory->createRequest($request);
    }

    /**
     * Converts Psr7Request to symfony request
     *
     * @param ServerRequestInterface $request
     *
     * @return SymfonyRequest
     */
    public function fromPsr7(ServerRequestInterface $request)
    {
        $httpFoundationFactory = new HttpFoundationFactory();
        return $httpFoundationFactory->createRequest($request);
    }
}