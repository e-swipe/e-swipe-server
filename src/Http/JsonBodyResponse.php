<?php

namespace App\Http;


use Cake\Http\Response;

class JsonBodyResponse
{
    /**
     * @param Response $response
     * @param $body
     * @return Response
     */
    public static function okResponse(Response $response, $body)
    {
        return $response->withStatus(200)->withType('json')->withStringBody(json_encode($body));
    }

    /**
     * @param Response $response
     * @param $body
     * @return Response
     */
    public static function createdResponse(Response $response, $body)
    {
        return $response->withStatus(201)->withType('json')->withStringBody(json_encode($body));
    }
}
