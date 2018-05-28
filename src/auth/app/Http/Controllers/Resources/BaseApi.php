<?php

namespace App\Http\Controllers\Resources;

/**
 * Base Class for the API controllers.
 */
abstract class BaseApi extends Controller
{
    //API Http codes
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_ACCEPTED = 202;
    const HTTP_INVALID_REQUEST = 400;
    const HTTP_AUTH_ERROR = 401;
    const HTTP_NOT_FOUND = 404;
    const HTTP_CONFLICT = 409;
    const HTTP_VALIDATION_ERROR = 422;
    const HTTP_SERVER_ERROR = 500;
}