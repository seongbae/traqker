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
    public function test_task_can_be_created()
    {
        $data =
            [
                'name'=>$this->faker->sentence,
                'description'=>$this->faker->paragraph
            ];

        $this->actingAs($this->user)
            ->post('/tasks', $data);
            //->assertStatus(200)

        $this->assertCount(1, Task::all());;
    }
}
