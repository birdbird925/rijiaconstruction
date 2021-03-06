<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Terbilang;

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

        if(!$this->material_included)
            $total += $this->materialTotal();

        // if($this->discount)
        //     $total -= $this->discount;
            
        return $total;
    }

    public function materialTotal()
    {
        $total = 0;
        foreach($this->materials as $material)
            $total += ($material->quantity * $material->price);
        return $total;
    }

    public function priceInText()
    {
        $priceInText = preg_replace('/,+/', '', Terbilang::make($this->total() - $this->deposit - ($this->discount ? $this->discount : 0), ' ONLY'));
        return strtoupper($priceInText);
    }

    public function refNumber()
    {
        return '#'.str_pad($this->id, 7, '0', STR_PAD_LEFT);
    }
}
