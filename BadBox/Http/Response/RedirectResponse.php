<?php


namespace BadBox\Http\Response;


use BadBox\Http\ResponseCode;

class RedirectResponse
    extends Response
{
    private string $url;

    public function __construct(string $url)
    {
        $this->url = urlencode($url);
    }

    public function getResponseCode(): int
    {
        return ResponseCode::MOVED_PERMANENTLY;
    }

    public function render(): void
    {
        header('Location: ' . $this->url);
    }
}
