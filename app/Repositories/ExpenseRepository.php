<?php

namespace App\Repositories;

use App\Models\Approval;
use App\Models\ApprovalStage;
use App\Models\Approver;
use App\Models\Expense;
use App\Models\Status;
use App\Repositories\Interfaces\ExpenseRepositoryInterface;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    public function create(array $data)
    {
        $status = Status::where('name', 'Menunggu Persetujuan')->first();
        $data['status_id'] = $status->id;

        return Expense::create($data);
    }

    public function approve($id, $approverId)
    {
        $expense = Expense::findOrFail($id);
        $totalTahapApproval = ApprovalStage::count();
        $currentStage = Approval::where('expense_id', $id)->orderBy('id', 'asc')->count();

        if ($totalTahapApproval == $currentStage) {
            throw new \Exception('Tidak ada tahap approval yang tersisa.');
        }

        $currentStageApprover = ApprovalStage::orderBy('id', 'ASC')->skip($currentStage)->take(1)->first();
        $stageApprover = ApprovalStage::where('approver_id', $approverId)->first();

        if ($currentStageApprover && $stageApprover) {
            $checkStatusApproval = Approval::where('expense_id', $id)->where('approver_id', $stageApprover->id)->first();

            if ($checkStatusApproval) {
                throw new \Exception('Tahap approval sudah dilakukan.');
            }
            
            if ($currentStageApprover->id != $stageApprover->id) {
                throw new \Exception('Tahap approval tidak sesuai.');
            }

            $approval = Approval::create([
                'expense_id' => $id,
                'approver_id' => $approverId,
                'status_id' => Status::where('name', 'Disetujui')->first()->id,
            ]);

            // Perbarui status expense jika semua tahap selesai
            if ($totalTahapApproval == $currentStage + 1) {
                $expense->update(['status_id' => Status::where('name', 'Disetujui')->first()->id]);
            }
            
            return $approval;
        } else {
            throw new \Exception('Internal server error.');
        }
    }

    public function getById($id) {
        $expense = Expense::select('id', 'amount', 'status_id')
            ->with([
                'status:id,name',
                'approvals' => function($query) {
                    $query->select('id', 'expense_id', 'approver_id', 'status_id');
                },
                'approvals.approver:id,name',
                'approvals.status:id,name',
            ])->findOrFail($id);

        $expense->makeHidden(['status_id']);
        $expense->approvals->makeHidden(['expense_id', 'approver_id', 'status_id']);

        return $expense;
    }
}