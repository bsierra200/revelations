<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Plates\PlatesRenderer;
use Mezzio\Router;
use Mezzio\Template;
use Mezzio\Twig\TwigRenderer;
use Mezzio\LaminasView\LaminasViewRenderer;

class HomePageHandler implements RequestHandlerInterface
{
    private $containerName;

    private $router;

    private $template;

    public function __construct(
        Router\RouterInterface $router,
        Template\TemplateRendererInterface $template = null,
        string $containerName
    ) {
        $this->router        = $router;
        $this->template      = $template;
        $this->containerName = $containerName;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
       return new JsonResponse([],200);
    }
}
