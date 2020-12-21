<?php

namespace BadBox\Http\Response;

use BadBox\Http\ResponseCode;

class BasicResponse
    extends Response
{

    public string $content;
    public int $responseCode;

    public function __construct(string $content, int $responseCode = ResponseCode::OK, $mimeType = "text/html")
    {
        $this->content = $content;
        $this->responseCode = $responseCode;
        header("Content-Type: $mimeType");
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    public function render(): void
    {
        echo $this->content;
    }
}
