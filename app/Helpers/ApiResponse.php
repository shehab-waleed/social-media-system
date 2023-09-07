<?php

namespace App\Helpers;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse
{
    public static function send($code = 200, $msg = null, $data = null)
    {
        $response = [
            'status' => $code,
            'msg' => $msg,
            'data' => $data,
        ];

        return new JsonResponse(
            data: $response,
            status: $code,
        );
    }
}
