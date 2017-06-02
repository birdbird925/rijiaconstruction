<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Invoice extends Model
{
    protected $guarded = [];
    protected $table = 'invoices';
    public $timestamps  = false;

    public function services()
    {
        return $this->hasMany(Service::class, 'form_id')->where('form_type', 'App\Invoice');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'form_id')->where('form_type', 'App\Invoice');
    }

    public function total()
    {
        $total = 0;
        foreach($this->services as $services)
            $total += $services->price;
        return $total;
    }

    public function refNumber()
    {
        return '#'.str_pad($this->id, 7, '0', STR_PAD_LEFT);
    }
}
