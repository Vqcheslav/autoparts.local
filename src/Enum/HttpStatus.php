<?php

namespace App\Enum;

enum HttpStatus: int
{
    case Ok = 200;

    case Created = 201;

    case NoContent = 204;

    case MovedPermanently = 301;

    case Found = 302;

    case NotModified = 304;

    case BadRequest = 400;

    case Unauthorized = 401;

    case Forbidden = 403;

    case NotFound = 404;

    case MethodNotAllowed = 405;

    case Conflict = 409;

    case UnprocessableContent = 422;

    case TooManyRequests = 429;

    case InternalServerError = 500;

    case NotImplemented = 501;

    case BadGateway = 502;

    case ServiceUnavailable = 503;

    case GatewayTimeout = 504;
}
