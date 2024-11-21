<?php

namespace App\Repositories;

use App\Models\ApprovalStage;
use App\Repositories\Interfaces\ApprovalStageRepositoryInterface;

class ApprovalStageRepository implements ApprovalStageRepositoryInterface
{
    public function create(array $data)
    {
        return ApprovalStage::create($data);
    }

    public function update($id, array $data)
    {
        $stage = ApprovalStage::findOrFail($id);
        $stage->update($data);

        return $stage;
    }
}