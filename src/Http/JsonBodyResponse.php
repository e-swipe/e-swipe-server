<?php

namespace App\Http;


use Cake\Http\Response;
use Eswipe\Model\FieldError;

class JsonBodyResponse
{
    public static function okResponse(Response $response, $body)
    {
        return $response->withStatus(200)->withType('json')->withStringBody(json_encode($body));
    }

    public static function createdResponse(Response $response, $body)
    {
        return $response->withStatus(201)->withType('json')->withStringBody(json_encode($body));
    }
}
