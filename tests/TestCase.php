<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Helpers\TraitTestHelpers;
use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, TraitTestHelpers;
    
    /** @var string */
    private $authToken;

    /** @var User */
    private $authUser;

    /** @var Factory **/
    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    /**
     * @param string $url
     * @param array $data
     * @return TestResponse
     */
    public function authPostJson(string $url, array $data = []): TestResponse
    {
        $headers = ['Authorization' => 'Bearer ' . $this->getAuthToken()];
        return $this->postJson($url, $data, $headers);
    }

    /**
     * @param string $url
     * @param array $data
     * @return TestResponse
     */
    public function authPatchJson(string $url, array $data = []): TestResponse
    {
        $headers = ['Authorization' => 'Bearer ' . $this->getAuthToken()];

        return $this->patchJson($url, $data, $headers);
    }

    /**
     * @param string $url
     * @param array $data
     * @return TestResponse
     */
    public function authPutJson(string $url, array $data = []): TestResponse
    {
        $headers = ['Authorization' => 'Bearer ' . $this->getAuthToken()];

        return $this->putJson($url, $data, $headers);
    }

    public function authGetJson(string $url): TestResponse
    {
        $headers = ['Authorization' => 'Bearer ' . $this->getAuthToken()];

        return $this->getJson($url, $headers);
    }

    public function authDeleteJson(string $url)
    {
        $headers = ['Authorization' => 'Bearer ' . $this->getAuthToken()];

        return $this->deleteJson($url, [], $headers);
    }

    /**
     * @return User
     */
    protected function getAuthUser(): User
    {
        if (!$this->authUser) {
            $this->installPassport();
            $this->authToken = $this->tokenForUser();
        }

        return $this->authUser;
    }

    /**
     * @return string
     */
    public function getAuthToken(): string
    {
        if (!$this->authToken) {
            // default to student
            $this->installPassport();
            $this->authToken = $this->tokenForUser();
        }

        return $this->authToken;
    }

    /**
     * @return TestCase
     */
    public function asUser(): TestCase
    {
        if (!$this->authUser) {
            $this->installPassport();
            $this->authUser = $this->tokenForUser();
        }

        return $this;
    }

    private function tokenForUser(): string
    {
        $this->authTeacher = factory(User::class)->create([
            'email' => 'brice.thomas2209@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        $response = $this
            ->postJson('api/users/oauth/token', [
                'username' => 'brice.thomas2209@gmail.com',
                'password' => '123456'
            ]);
        return json_decode($response->getOriginalContent(), true)['access_token'];
    }
}
