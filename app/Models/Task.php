<?php

namespace App\Models;
use App\Models\User;
use App\Models\District;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    
    protected $table = 'task';
    

    protected $fillable = [
        'user_id',
        'task_name',
        'origin_address',
        'destination_address',
        'origin_latitude',
        'origin_longitude',
        'destination_latitude',
        'destination_longitude',
        'task_status',
        'proff',
        'task_description',
        'assign_from',
        'customer_id',
    ];
    public function user()
    {
        // return $this->belongsTo(User::class);
        //from user_id to id
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function getStatusLabelAttribute()
    {
        if ($this->task_status == 0) {
            return '<span class="badge badge-warning">Pending</span>';
        } elseif ($this->task_status == 1) {
            return '<span class="badge badge-success">Completed</span>';
        } else {
            return '<span class="badge badge-danger">Canceled</span>';
        }
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
