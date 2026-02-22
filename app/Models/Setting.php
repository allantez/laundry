<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use HasUuid, SoftDeletes;
    
    protected $fillable = ['key', 'value', 'branch_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
