<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table="customer";
    protected $fillable = [
        'name',
        'description',
    ];
    
    public function task()
    {
        return $this->hasMany(Task::class);
    }
}
