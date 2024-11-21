<?php

namespace Tests\Feature;

use App\Models\Approver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApproverTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_name_to_be_not_empty_or_null()
    {
        // Mengirim request POST dengan name null
        $response = $this->postJson('/api/approvers', [
            'name' => null
        ]);

        // Memastikan status 422 dan error pada field name
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');

        // Mengirim request POST dengan name kosong
        $response = $this->postJson('/api/approvers', [
            'name' => ''
        ]);

        // Memastikan status 422 dan error pada field name
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');

    }

    /** @test */
    public function it_requires_name_to_be_unique_when_adding_approver()
    {
        Approver::create(['name' => 'Ana']);

        $response = $this->postJson('/api/approvers', [
            'name' => 'Ana'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function it_can_create_an_approver_with_unique_name()
    {
        $response = $this->postJson('/api/approvers', [
            'name' => 'Ana'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('approvers', [
            'name' => 'Ana'
        ]);
    }
}
