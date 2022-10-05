<?php

namespace App\Models\CourierCompanies;

use App\Models\CourierCompanies\CourierCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientCourierCompany extends Model
{
    use HasFactory;
    protected $table = 'client_courier_companies';

    protected $casts = [
        'params' => 'array',
        'cities' => 'json'
    ];

    protected $fillable = [
        'priority', 'capacity', 'params', 'courier_company_id', 'is_default', 'cities'
    ];

    public function courierCompany()
    {
        return $this->belongsTo(CourierCompany::class);
    }

    // TODO Linked Cities with the table city wich is not found in current version

//    public function linkedCities()
//    {
//        return $this->belongsToJson(City::class, 'cities');
//    }
}
