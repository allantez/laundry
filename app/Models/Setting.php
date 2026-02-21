<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'branch_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
