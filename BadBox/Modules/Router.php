<?php /** @noinspection PhpIncludeInspection */

namespace BadBox\Modules;

use BadBox\Exceptions\BadBoxException;
use BadBox\Exceptions\BadResponseFromControllerException;
use BadBox\Exceptions\ForbiddenPathException;
use BadBox\Exceptions\RouteAlreadyExistsException;
use BadBox\Exceptions\RouteNotFoundException;
use BadBox\Http\Response\Response;
use BadBox\Http\ResponseCode;

// TODO Add regex based routes
final class Router
    extends Module
{

    private function __construct()
    {
    }

    /**
     * @var array
     */
    private array $routes = [];
    private array $errorRoute = [];

    /**
     * @param string $path
     * @param callable $routeFunction
     * @return $this
     * @throws RouteAlreadyExistsException
     */
    public function addRoute(string $path, callable $routeFunction): self
    {
        if (array_key_exists($path, $this->routes) || array_key_exists('/assets', $this->routes)) {
            throw new RouteAlreadyExistsException("The route $path already exists");
        }
        $this->routes[$path] = $routeFunction;
        return $this;
    }

    /**
     * @param int $errorCode
     * @param callable $routeFunction
     * @return $this
     * @throws RouteAlreadyExistsException
     */
    public function errorRoute(int $errorCode, callable $routeFunction): self
    {
        if (array_key_exists($errorCode, $this->routes)) {
            throw new RouteAlreadyExistsException("The error route $errorCode already exists");
        }
        $this->errorRoute[$errorCode] = $routeFunction;
        return $this;
    }

    /**
     * @param string $path
     * @throws BadBoxException
     */
    public function callRoute(string $path): void
    {
        if ($path === '/assets') {
            throw new BadBoxException("Can't direct access /assets route", 403);
        }
        if (str_starts_with($path, '/assets/')) {
            $assetFile = realpath(dirname(__BADBOX_ROOT_DIR__) . '/App/assets' . substr($path, 7));
            if (!file_exists($assetFile)) {
                throw new RouteNotFoundException($path);
            }
            if (is_dir($path)) {
                throw new ForbiddenPathException($path);
            }

            header('Content-Type: ' . self::getMimeTypeForFile($assetFile));
            include_once $assetFile;
            return;
        }
        if (!array_key_exists($path, $this->routes)) {
            throw new RouteNotFoundException($path);
        }
        $response = $this->routes[$path]();
        if (!($response instanceof Response)) {
            throw new BadResponseFromControllerException($path);
        }
        $response->render();
        http_response_code($response->getResponseCode());
    }

    public function callErrorRoute(int $httpCode, $errorMessage): void
    {
        if (!array_key_exists($httpCode, $this->errorRoute)) {
            self::printBasicErrorPage($httpCode, $errorMessage);
            return;
        }
        $response = $this->errorRoute[$httpCode]($errorMessage);
        if (!($response instanceof Response)) {
            self::printBasicErrorPage(
                500,
                (new BadResponseFromControllerException($httpCode))->getMessage()
            );
        }
        try {
            $response->render();
            http_response_code($response->getResponseCode());
        } catch (BadBoxException $e) {
            self::printBasicErrorPage(500, $e->getMessage());
        }
    }

    private static function printBasicErrorPage(int $httpCode, string $detailedMessage): void
    {
        $errorReason = null;
        $errorDescription = null;
        try {
            $errorReason = ResponseCode::getName($httpCode);
        } catch (BadBoxException $e) {
            $errorReason = "Unknown Error";
        }
        try {
            $errorDescription = ResponseCode::getDescription($httpCode);
        } catch (BadBoxException $e) {
            $errorDescription = "We don't know what happened as error $httpCode is not registered internally";
        }
        echo <<<DEFAULT_ERROR_PAGE
<h1>Error $httpCode : $errorReason</h1>
<p>$errorDescription</p>
<hr>
<h2>Detailed Message</h2>
<p>$detailedMessage</p>
DEFAULT_ERROR_PAGE;
    }

    private const DEFAULT_MIME_TYPE = "application/octet-stream";
    private const MIME_TYPES = [
        'js'    => 'application/javascript',
        'json'  => 'application/json',
        'xml'   => 'application/xml',
        'css'   => 'text/css',
        'html'  => 'text/html',
        'png'   => 'image/png',
        'svg'   => 'image/svg+xml',
        'jpg'   => 'image/jpeg',
        'webp'  => 'image/webp',
        'ico'   => 'image/x-icon',
        'jpeg'  => 'image/jpeg',
        'ttf'   => 'font/ttf',
        'otf'   => 'font/otf',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
    ];

    private static function getMimeTypeForFile(string $filePath): string
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (array_key_exists($extension, self::MIME_TYPES)) {
            return self::MIME_TYPES[$extension];
        }
        return self::DEFAULT_MIME_TYPE;
    }

    private static Router $_instance;

    public static function getInstance(): self
    {
        return self::$_instance ?? (self::$_instance = new Router());
    }
}
