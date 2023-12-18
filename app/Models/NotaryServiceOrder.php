<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaryServiceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'main_category',
        'sub_category',
        'description_of_service',
        'doc_1',
        'doc_2',
        'doc_3',
        'date_of_signing',
        'start_date',
        'end_date',
        'value',
        'monthly_rent',
        'advance_amount',
        'village_officer_number',
        'devisional_sec',
        'local_gov',
        'district',
        'land_reg_office',
        'notary_person_json',
        'payment_status',
        'order_status',
        'create_time',
        'modified_time'
    ];

    public function find_all() {
        $map['order_status'] = 0;

        return $this->where($map)->get();
    }
}
