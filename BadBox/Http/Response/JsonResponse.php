<?php


namespace BadBox\Http\Response;


use BadBox\Http\ResponseCode;

class JsonResponse
    extends Response
{

    private int $responseCode;
    private $contentObject;

    public function __construct($contentObject, int $responseCode = ResponseCode::OK)
    {
        $this->responseCode = $responseCode;
        $this->contentObject = $contentObject;
        header("Content-Type: application/json");
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    public function render(): void
    {
        echo json_encode($this->contentObject);
    }
}
