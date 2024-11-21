<?php

namespace Tests\Feature;

use App\Models\ApprovalStage;
use Tests\TestCase;
use App\Models\Approver;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApprovalStageTest extends TestCase
{
    use RefreshDatabase;

    // Create
    /** @test */
    public function it_requires_approver_id_to_be_not_empty_or_null()
    {
        $response = $this->postJson('/api/approval-stages', [
            'approver_id' => null
        ]);

        // Memastikan status 422 dan error pada field name
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('approver_id');

        // Mengirim request POST dengan name kosong
        $response = $this->postJson('/api/approval-stages', [
            'approver_id' => ''
        ]);

        // Memastikan status 422 dan error pada field name
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('approver_id');

    }

    /** @test */
    public function it_requires_approver_id_to_be_unique_when_adding_approver()
    {
        $approver = Approver::create(['name' => 'Ana']);
        ApprovalStage::create(['approver_id' => $approver->id]);

        $response = $this->postJson('/api/approval-stages', [
            'approver_id' => $approver->id
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('approver_id');
    }

    /** @test */
    public function it_can_create_an_approver_with_unique_name()
    {
        $approver = Approver::create(['name' => 'Ana']);

        $response = $this->postJson('/api/approval-stages', [
            'approver_id' => $approver->id
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('approval_stages', [
            'approver_id' => $approver->id
        ]);
    }

    // Update
    public function test_update_approval_stage()
    {
        $approverA = Approver::create([
            'name' => 'Approver A'
        ]);
        $approverB = Approver::create([
            'name' => 'Approver B'
        ]);

        $approvalStage = ApprovalStage::create([
            'approver_id' => $approverA->id
        ]);

        $response = $this->putJson('/api/approval-stages/' . $approvalStage->id, [
            'approver_id' => $approverB->id
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('approval_stages', [
            'id' => $approvalStage->id,
            'approver_id' => $approverB->id
        ]);
    }

    public function test_update_approval_stage_invalid_approver()
    {
        $approver = Approver::create([
            'name' => 'Approver A'
        ]);

        $approvalStage = ApprovalStage::create([
            'approver_id' => $approver->id,
        ]);

        $response = $this->putJson('/api/approval-stages/' . $approvalStage->id, [
            'approver_id' => 999,
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['approver_id']);
    }
}
