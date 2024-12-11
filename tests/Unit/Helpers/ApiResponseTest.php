<?php

namespace Tests\Unit\Helpers;

use App\Helpers\ApiResponse;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ApiResponseTest extends TestCase
{
    public function test_it_has_send_abstract_method()
    {
        $reflection = new ReflectionClass(ApiResponse::class);
        $method = $reflection->getMethod('send');

        $this->assertTrue($reflection->hasMethod('send'));
        $this->assertTrue($method->isStatic());
        $this->assertTrue($method->isPublic());
    }
    public function test_it_should_return_a_valid_response_object_with_valid_data()
    {
        $data = [
            'foo' => 'bar'
        ];

        $response = ApiResponse::send(
            200,
            'This is a success response',
            $data
        );

        $this->assertEquals(200 , $response->getStatusCode());
        $this->assertEquals([
            'status' => 200,
            'msg' => 'This is a success response',
            'data' => $data
        ], json_decode($response->getContent() , true));
    }

    public function test_it_should_return_default_response_status_and_data(){
        $response = ApiResponse::send();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            'status' => 200,
            'msg' => null,
            'data' => null
        ] , json_decode($response->getContent() , true));
    }
}
