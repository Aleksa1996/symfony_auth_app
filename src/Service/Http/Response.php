<?php


namespace App\Service\Http;


use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Response
{

    /**
     * Converts symfony response to psr7 response
     *
     * @param SymfonyResponse $response
     *
     * @return ResponseInterface
     */
    public function toPsr7(SymfonyResponse $response)
    {
        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
        return $psrHttpFactory->createResponse($response);
    }


    /**
     * Converts psr7 response to symfony response
     *
     * @param ResponseInterface $response
     *
     * @return SymfonyResponse|StreamedResponse
     */
    public function fromPsr7(ResponseInterface $response)
    {
        $httpFoundationFactory = new HttpFoundationFactory();
        return $httpFoundationFactory->createResponse($response);
    }
}