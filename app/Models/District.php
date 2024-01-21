<?php

namespace App\Models;
use App\Models\User;
use App\Models\Task;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function task()
    {
        return $this->hasMany(Task::class);
    }
}