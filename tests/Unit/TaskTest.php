<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Log;
use Faker\Factory;

class TaskTest extends TestCase
{
    protected $user;
    protected $faker;

    use RefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();

        $this->user = User::factory()->make();
        $this->faker = Factory::create();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_task_list_can_be_retrieved_from_api()
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
    public function test_task_detail_can_be_retrieved_from_api()
    {

        Sanctum::actingAs(
            $this->user,
            ['*']
        );

        $task = Task::factory()->make();

        $response = $this->get('/api/tasks/'.$task->id);

        $response->assertOk();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_task_can_be_created_from_api()
    {
        Sanctum::actingAs(
            $this->user,
            ['*']
        );

        $data =
            [
                'name'=>$this->faker->sentence,
                'status'=>'complete',
                'user_id'=>$this->user->id,
                'description'=>$this->faker->paragraph
            ];

        $this->post('/api/tasks/', $data)
            ->assertStatus(200);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_task_can_be_updated_from_api()
    {

        Sanctum::actingAs(
            $this->user,
            ['*']
        );

        $task = Task::factory()->make(
            [
                'name'=>'test task 1',
                'status'=>'active',
                'description'=>'test description 1'
            ]
        );

        $data = [
            'name'=>$this->faker->sentence,
            'status'=>'complete',
            'description'=>$this->faker->paragraph
        ];

        $this->post('/api/tasks/'.$task->id, $data)
            ->assertStatus(200);

    }
}
