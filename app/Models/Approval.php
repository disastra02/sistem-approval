<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Approval extends Model
{
    use HasFactory;
    protected $fillable = [
        'expense_id',
        'approver_id',
        'status_id',
    ];

    public function approver(): HasOne
    {
        return $this->hasOne(Approver::class, 'id', 'approver_id');
    }

    public function status(): HasOne
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }
}
