<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Quotation extends Model
{
    protected $guarded = [];
    protected $table = 'quotations';
    public $timestamps  = false;

    public function services()
    {
        return $this->hasMany(Service::class, 'form_id')->where('form_type', 'App\Quotation');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'form_id')->where('form_type', 'App\Quotation');
    }

    public function total()
    {
        $total = 0;
        foreach($this->services as $services)
            $total += $services->price;

        if(!$this->material_included)
            $total += $this->materialTotal();
        return $total;
    }

    public function refNumber()
    {
        return '#'.str_pad($this->id, 7, '0', STR_PAD_LEFT);
    }

    public function materialTotal()
    {
        $total = 0;
        foreach($this->materials as $material)
            $total += ($material->quantity * $material->price);
        return $total;
    }
}
