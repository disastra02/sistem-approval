<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_expense_approval_process()
    {
        // Menjalankan seeder yang diperlukan
        Artisan::call('db:seed', ['--class' => 'StatusTableSeeder']);

        // **Step 1**: Buat approver (Ana, Ani, Ina)
        $responseAna = $this->postJson('/api/approvers', ['name' => 'Ana']);
        $responseAni = $this->postJson('/api/approvers', ['name' => 'Ani']);
        $responseIna = $this->postJson('/api/approvers', ['name' => 'Ina']);

        $responseAna->assertStatus(201);
        $responseAni->assertStatus(201);
        $responseIna->assertStatus(201);

        $approverAna = $responseAna->json();
        $approverAni = $responseAni->json();
        $approverIna = $responseIna->json();

        // **Step 2**: Buat tahap approval
        $stage1 = $this->postJson('/api/approval-stages', ['approver_id' => $approverAna['data']['id']]);
        $stage2 = $this->postJson('/api/approval-stages', ['approver_id' => $approverAni['data']['id']]);
        $stage3 = $this->postJson('/api/approval-stages', ['approver_id' => $approverIna['data']['id']]);

        $stage1->assertStatus(201);
        $stage2->assertStatus(201);
        $stage3->assertStatus(201);

        // **Step 3**: Buat pengeluaran (4 pengeluaran)
        $expense1 = $this->postJson('/api/expense', ['amount' => 100]);
        $expense2 = $this->postJson('/api/expense', ['amount' => 150]);
        $expense3 = $this->postJson('/api/expense', ['amount' => 200]);
        $expense4 = $this->postJson('/api/expense', ['amount' => 250]);

        $expense1->assertStatus(201);
        $expense2->assertStatus(201);
        $expense3->assertStatus(201);
        $expense4->assertStatus(201);

        $expense1Id = $expense1->json()['data']['id'];
        $expense2Id = $expense2->json()['data']['id'];
        $expense3Id = $expense3->json()['data']['id'];
        $expense4Id = $expense4->json()['data']['id'];

        // **Step 4**: Proses approval pengeluaran
        // Pengeluaran 1: disetujui semua
        $this->patchJson("/api/expense/{$expense1Id}/approve", ['approver_id' => $approverAna['data']['id']])->assertStatus(201);
        $this->patchJson("/api/expense/{$expense1Id}/approve", ['approver_id' => $approverAni['data']['id']])->assertStatus(201);
        $this->patchJson("/api/expense/{$expense1Id}/approve", ['approver_id' => $approverIna['data']['id']])->assertStatus(201);

        $this->getJson("/api/expense/{$expense1Id}")
            ->assertStatus(200)
            ->assertJson(['data' => ['status' => ['name' => 'Disetujui']]]);

        // Pengeluaran 2: disetujui dua approver
        $this->patchJson("/api/expense/{$expense2Id}/approve", ['approver_id' => $approverAna['data']['id']])->assertStatus(201);
        $this->patchJson("/api/expense/{$expense2Id}/approve", ['approver_id' => $approverAni['data']['id']])->assertStatus(201);

        $this->getJson("/api/expense/{$expense2Id}")
            ->assertStatus(200)
            ->assertJson(['data' => ['status' => ['name' => 'Menunggu Persetujuan']]]);

        // Pengeluaran 3: disetujui satu approver
        $this->patchJson("/api/expense/{$expense3Id}/approve", ['approver_id' => $approverAna['data']['id']])->assertStatus(201);

        $this->getJson("/api/expense/{$expense3Id}")
            ->assertStatus(200)
            ->assertJson(['data' => ['status' => ['name' => 'Menunggu Persetujuan']]]);

        // Pengeluaran 4: belum disetujui
        $this->getJson("/api/expense/{$expense4Id}")
            ->assertStatus(200)
            ->assertJson(['data' => ['status' => ['name' => 'Menunggu Persetujuan']]]);
    }
}
