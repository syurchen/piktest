<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class TestAPI extends BaseTestCase
{
    use CreatesApplication;
    protected function setUp(): void
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'http://127.0.0.1:8000/api/'
	]);
	$this->rand = rand(5, 10000);

	$this->perPage = 10;
	$this->page = 3;
    }

    public function testRegister()
    {
	$randLogin = 'test' . $this->rand;
	$data = [
	    'name' => $randLogin,
	    'email' => 'test' . $randLogin . '@mail.ru',
	    'password' => md5($randLogin),
	    'c_password' => md5($randLogin),
	];
        $response = json_decode($this->client->request('POST', 'register', [\GuzzleHttp\RequestOptions::JSON => $data])->getBody()->getContents(), true);
        $this->assertTrue(isset($response['success']['token']));
        return $data;
    }

    /**
    * @depends testRegister
    */
    public function testLogin(array $data)
    {
	
	$response = json_decode($this->client->request('POST', 'login', [\GuzzleHttp\RequestOptions::JSON => $data])->getBody()->getContents(), true);
	$this->assertTrue(isset($response['success']['token']));
	return $response['success']['token'];
    }

    /**
    * @depends testLogin
    */
    public function testAdd($token)
    {
	$data = [
	    'city' => 'Moscow' . $this->rand,
	    'district' => 'Degunino' . $this->rand,
	    'address' => 'Angarskaya ' . $this->rand,
	    'residence' => 'New ' . $this->rand,
	    'block' => $this->rand,
	    'floors' => $this->rand,
	    'floor' => floor($this->rand/2),
	    'rooms' => floor($this->rand/5),
	    'square' => floor($this->rand/4),
	    'rant' => floor($this->rand) * 100,
	    'api_token' => $token,
	];
	$response = json_decode($this->client->request('POST', 'add', [\GuzzleHttp\RequestOptions::JSON => $data])->getBody()->getContents(), true);
	$this->assertTrue(isset($response['success']));
	return $token;
    }

    /**
    * @depends testAdd
    */
    public function testSearch($token)
    {
	$data = [
	    'query' => [
		'city' => 'Moscow',
		'district' => 'Degunino'
	    ],
	    'per_page' => $this->perPage,
	    'page' => $this->page,
	    'api_token' => $token,
	];
	$response = json_decode($this->client->request('POST', 'search', [\GuzzleHttp\RequestOptions::JSON => $data])->getBody()->getContents(), true);
	$this->assertTrue(isset($response['success']));
	$this->assertEquals(count($response['success']['flats']), $this->perPage);
    }

}