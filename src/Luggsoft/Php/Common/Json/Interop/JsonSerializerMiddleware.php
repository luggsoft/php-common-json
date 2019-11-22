<?php

namespace Luggsoft\Php\Common\Json\Interop;

use Luggsoft\Php\Common\Json\JsonSerializerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JsonSerializerMiddleware implements MiddlewareInterface
{
    
    /**
     *
     * @var JsonSerializerInterface
     */
    private $jsonSerializer;
    
    /**
     *
     * @param JsonSerializerInterface $jsonSerializer
     */
    public function __construct(JsonSerializerInterface $jsonSerializer)
    {
        $this->jsonSerializer = $jsonSerializer;
    }
    
    /**
     *
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler): ResponseInterface
    {
        $contentType = $request->getHeaderLine('Content-Type');
        
        if (strstr($contentType, 'application/json') !== false) {
            $body = $request->getBody()->getContents();
            $parsedBody = $this->jsonSerializer->decode($body);
            $request = $request->withParsedBody($parsedBody);
        }
        
        return $requestHandler->handle($request);
    }
    
}
