<?php

namespace BadBox\Http\Response;

use BadBox\Http\ResponseCode;
use BadBox\Modules\Twig;

class TemplateResponse
    extends Response
{

    private string $templateName;
    private array $context;
    private int $responseCode;

    public function __construct(string $templateName, array $context = [], int $responseCode = ResponseCode::OK)
    {
        $this->templateName = $templateName . '.twig';
        $this->context = $context;
        $this->responseCode = $responseCode;
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    public function render(): void
    {
        echo Twig::getInstance()
                 ->render($this->templateName, $this->context);
    }
}
