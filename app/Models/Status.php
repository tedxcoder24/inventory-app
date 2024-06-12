<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'enabled'];

    public function changeItemStatuses()
    {
        return $this->hasMany(ChangeItemStatus::class);
    }
}
