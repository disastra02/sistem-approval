<?php

namespace App\Repositories\Interfaces;

interface ApprovalStageRepositoryInterface
{
    public function create(array $data);
    public function update($id, array $data);
}