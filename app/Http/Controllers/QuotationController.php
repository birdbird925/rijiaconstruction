<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Quotation;
use App\Service;
use App\Material;
use Carbon\Carbon;
use PDF;

class QuotationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $quotations = Quotation::orderBy('id', 'DESC')->get();
        return view('admin.quotation.index', compact('quotations'));
    }

    public function create()
    {
        return view('admin.quotation.create');
    }

    public function store()
    {
        $quotation = Quotation::create([
            'date' => Carbon::createFromFormat('m/d/Y', request('date')),
            'to' => request('customer'),
            'title' => request('title'),
            'status' => 0,
        ]);

        if(request('service') != '') {
            foreach(request('service') as $service) {
                $service = Service::create([
                    'form_id' => $quotation->id,
                    'form_type' => 'App\Quotation',
                    'text' => $service['text'],
                    'price' => $service['price']
                ]);
            }
        }

        if(request('material') != '') {
            foreach(request('material') as $material) {
                $material = Material::create([
                    'form_id' => $quotation->id,
                    'form_type' => 'App\Quotation',
                    'text' => $material['text'],
                    'quantity' => $material['quantity'],
                    'unit' => $material['unit'],
                    'price' => $material['price']
                ]);
            }
        }

        session()->flash('success', 'Quotation create successful!');
        return redirect('/admin/quotation');
    }

    public function edit($id)
    {
        $quotation = Quotation::findOrFail($id);
        return view('admin.quotation.edit', compact('quotation'));
    }

    public function update($id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->date = Carbon::createFromFormat('m/d/Y', request('date'));
        $quotation->to = request('customer');
        $quotation->title = request('title');
        $quotation->update();

        DB::table('services')->where('form_id', $quotation->id)->where('form_type', 'App\Quotation')->delete();
        DB::table('materials')->where('form_id', $quotation->id)->where('form_type', 'App\Quotation')->delete();

        if(request('service') != '') {
            foreach(request('service') as $service) {
                $service = Service::create([
                    'form_id' => $quotation->id,
                    'form_type' => 'App\Quotation',
                    'text' => $service['text'],
                    'price' => $service['price']
                ]);
            }
        }

        if(request('material') != '') {
            foreach(request('material') as $material) {
                $material = Material::create([
                    'form_id' => $quotation->id,
                    'form_type' => 'App\Quotation',
                    'text' => $material['text'],
                    'quantity' => $material['quantity'],
                    'unit' => $material['unit'],
                    'price' => $material['price']
                ]);
            }
        }
        session()->flash('success', 'Quotation update successful!');
        return redirect('/admin/quotation');
    }

    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);
        DB::table('services')->where('form_id', $quotation->id)->where('form_type', 'App\Quotation')->delete();
        DB::table('materials')->where('form_id', $quotation->id)->where('form_type', 'App\Quotation')->delete();
        $quotation->delete();
        session()->flash('success', 'Quotation delete successful!');
        return redirect('/admin/quotation');
    }

    public function preview()
    {
        $total = 0;
        if(request('service') != '') {
            foreach(request('service') as $service)
                $total += $service['price'];
        }

        $data = [
            'date' => request('date') ? Carbon::createFromFormat('m/d/Y', request('date'))->format('d F, Y') : '',
            'customer' => request('customer'),
            'title' => request('title'),
            'services' => request('service') ? request('service') : [],
            'materials' => request('material') ? request('material') : [],
            'total' => number_format($total, 2),
        ];
        $pdf = PDF::loadView('admin.quotation.pdf', $data);
        return $pdf->stream('quotation.pdf');
    }

    public function pdf($id)
    {
        $quotation = Quotation::findOrFail($id);
        $data = [
            'date' => Carbon::createFromFormat('Y-m-d H:i:s', $quotation->date)->format('d F, Y'),
            'customer' => $quotation->to,
            'title' => $quotation->title,
            'services' => $quotation->services ? $quotation->services->toArray() : [],
            'materials' => $quotation->materials ? $quotation->materials->toArray() : [],
            'total' => number_format($quotation->total(), 2),
        ];
        $pdf = PDF::loadView('admin.quotation.pdf', $data);
        return $pdf->stream('quotation.pdf');
    }

    public function printPDF($id)
    {
        $quotation = Quotation::findOrFail($id);
        $data = [
            'date' => Carbon::createFromFormat('Y-m-d H:i:s', $quotation->date)->format('d F, Y'),
            'customer' => $quotation->to,
            'title' => $quotation->title,
            'services' => $quotation->services ? $quotation->services->toArray() : [],
            'materials' => $quotation->materials ? $quotation->materials->toArray() : [],
            'total' => number_format($quotation->total(), 2),
            'print' => 1,
        ];
        $pdf = PDF::loadView('admin.quotation.pdf', $data);
        return $pdf->stream('quotation.pdf');
    }
}
