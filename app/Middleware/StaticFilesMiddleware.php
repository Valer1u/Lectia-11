<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;
use Slim\Psr7\Stream;

class StaticFilesMiddleware
{
    protected string $publicPath;
    protected array $extensions;

    public function __construct(string $publicPath, array $extensions = [])
    {
        $this->publicPath = rtrim($publicPath, '/');
        $this->extensions = $extensions ?: [
            'css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'svg', 'webp'
        ];
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $uriPath = $request->getUri()->getPath();

        // nu interceptăm rădăcina
        if ($uriPath === '/' || $uriPath === '') {
            return $handler->handle($request);
        }

        $ext = strtolower(pathinfo($uriPath, PATHINFO_EXTENSION));

        // dacă nu e extensie statică -> mergem mai departe în pipeline
        if (!in_array($ext, $this->extensions, true)) {
            return $handler->handle($request);
        }

        // construim calea completă către fișier
        $filePath = realpath($this->publicPath . $uriPath);

        // securitate + verificare existență
        if ($filePath === false || !str_starts_with($filePath, $this->publicPath) || !is_file($filePath)) {
            return $handler->handle($request);
        }

        $response = new SlimResponse();
        $stream = new Stream(fopen($filePath, 'rb'));
        $response = $response->withBody($stream);

        // tip MIME
        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';

        return $response->withHeader('Content-Type', $mimeType);
    }
}
