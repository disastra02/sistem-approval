<?php

namespace App\Repositories\Interfaces;

interface ExpenseRepositoryInterface
{
    public function create(array $data);
    public function approve($id, array $data);
    public function getById($id);
}