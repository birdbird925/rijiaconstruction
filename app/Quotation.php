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
        return $total;
    }

    public function refNumber()
    {
        return '#'.str_pad($this->id, 7, '0', STR_PAD_LEFT);
    }

    public function previewLink()
    {
        $url = '/admin/quotation/preview?date='.Carbon::createFromFormat('Y-m-d H:i:s', $this->date)->format('m/d/Y').'&customer='.$this->to.'&title='.$this->title;

        if($this->services != null){
            foreach($this->services as $key=>$service) {
                $parameter = '&service['.$key.']';
                $url .= $parameter.'[text]='.$service->text;
                $url .= $parameter.'[price]='.$service->price;
            }
        }

        if($this->materials != null) {
            foreach($this->materials as $key=>$material) {
                $parameter = '&material['.$key.']';
                $url .= $parameter.'[text]='.$material->text;
                $url .= $parameter.'[quantity]='.$material->quantity;
                $url .= $parameter.'[unit]='.$material->unit;
                $url .= $parameter.'[price]='.$material->price;
            }
        }

        return $url;
    }
}
