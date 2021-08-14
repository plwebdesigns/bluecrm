<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\User;
use Illuminate\Http\Request as Request;

class AdminControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * Test if agent info can be updated on agent roster page
     * 
     * @return void
     */
    public function test_if_user_info_can_be_updated(): void
    {
        $agent = factory(User::class)->make();

        dd($agent);
        $request = Request::create('/api/update_agent', 'POST', [
                'agent' => $agent
            ]);
    }
}
