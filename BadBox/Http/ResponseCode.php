<?php

namespace BadBox\Http;

use BadBox\Exceptions\BadBoxException;

final class ResponseCode
{

    // 2XX Success

    /**
     * The request has succeeded.
     */
    public const OK = 200;


    // 3XX Redirection
    public const MOVED_PERMANENTLY = 301;


    // 4XX Client Error
    /**
     * The server understood the request but refuses to authorize it.
     */
    public const FORBIDDEN = 403;
    /**
     * The origin server did not find a current representation for the target resource or is not willing to disclose that one exists.
     */
    public const NOT_FOUND = 404;

    // 5XX Server Error
    /**
     * The server encountered an unexpected condition that prevented it from fulfilling the request.
     */
    public const INTERNAL_SERVER_ERROR = 500;
    /**
     * The server does not support the functionality required to fulfill the request.
     */
    public const NOT_IMPLEMENTED = 501;

    /**
     * @param int $code The HTTP response code
     * @return string The name of the response code
     * @throws BadBoxException if no name for the error code can be found
     */
    public static function getName(int $code): string
    {
        switch ($code) {
            case self::OK:
                return "OK";
            case self::MOVED_PERMANENTLY:
                return "Moved Permanently";
            case self::FORBIDDEN:
                return "Forbidden";
            case self::NOT_FOUND:
                return "Not found";
            case self::INTERNAL_SERVER_ERROR:
                return "Internal Server Error";
            case self::NOT_IMPLEMENTED:
                return "Not Implemented";
        }
        throw new BadBoxException("No name for HTTP code $code");
    }

    /**
     * @param int $code The HTTP response code
     * @return string The long description of the response code
     * @throws BadBoxException if no description for the error code can be found.
     */
    public static function getDescription(int $code): string
    {
        switch ($code) {
            case self::OK:
                return "The request has succeeded.";
            case self::MOVED_PERMANENTLY:
                return "The target resource has been assigned a new permanent URI and any future references to this resource ought to use one of the enclosed URIs.";
            case self::FORBIDDEN:
                return "The server understood the request but refuses to authorize it.";
            case self::NOT_FOUND:
                return "The origin server did not find a current representation for the target resource or is not willing to disclose that one exists.";
            case self::INTERNAL_SERVER_ERROR:
                return "The server encountered an unexpected condition that prevented it from fulfilling the request.";
            case self::NOT_IMPLEMENTED:
                return "The server does not support the functionality required to fulfill the request.";
        }
        throw new BadBoxException("No description for code $code");
    }

    private function __construct()
    {
    }
}
