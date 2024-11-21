<?php

namespace App\Repositories;

use App\Models\Approver;
use App\Repositories\Interfaces\ApproverRepositoryInterface;

class ApproverRepository implements ApproverRepositoryInterface
{
    public function create(array $data)
    {
        return Approver::create($data);
    }
}