<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class TaskTest extends TestCase
{
    protected $user;

    //use RefreshDatabase;

    use DatabaseMigrations;

    public function setUp() :void
    {
        parent::setUp();

        $this->user = User::factory()->make();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_task_list_can_be_retrieved()
    {

        Sanctum::actingAs(
            $this->user,
            ['view-tasks']
        );

        $response = $this->get('/api/tasks');

        $response->assertOk();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_task_detail_can_be_retrieved()
    {

        Sanctum::actingAs(
            $this->user,
            ['view-tasks']
        );

        $task = Task::factory()->make();

        $response = $this->get('/api/task/'.$task->id);

        $response->assertOk();
    }
}
