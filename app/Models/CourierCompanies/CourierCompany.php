<?php

namespace App\Models\CourierCompanies;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierCompany extends Model
{
    use HasFactory;
    protected $table = 'courier_companies';

    protected $fillable = [
        'name',
        'status',
        'logo_url',
        'params'
    ];

    protected $casts = [
        'params' => 'array'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
