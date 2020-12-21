<?php

namespace BadBox\Modules;

use BadBox\Exceptions\BadBoxException;
use BadBox\Http\ResponseCode;
use Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twig
    extends Module
{

    private static self $_instance;
    private Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(dirname(__BADBOX_ROOT_DIR__) . '/App/templates');
        $options = [
            'strict_variables' => true,
            'cache'            => realpath(dirname(__BADBOX_ROOT_DIR__) . '/.twig_cache'),
            'debug'            => $_ENV['APP_DEBUG'],
        ];
        $this->twig = new Environment(
            $loader,
            $options
        );
    }

    public static function getInstance(): self
    {
        return self::$_instance ?? (self::$_instance = new self());
    }

    /**
     * @param string $templateName
     * @param array $context
     * @return string
     * @throws BadBoxException
     */
    public function render(string $templateName, array $context = []): string
    {
        try {
            return $this->twig->render($templateName, $context);
        } catch (Exception $e) {
            throw new BadBoxException($e->getMessage(), ResponseCode::INTERNAL_SERVER_ERROR);
        }
    }
}
