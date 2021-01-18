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
            'company' => request('company'),
            'title' => request('title'),
            'address_line_1' => request('address_line_1'),
            'address_line_2' => request('address_line_2'),
            'email' => request('email'),
            'tel' => request('tel'),
            'note' => request('note'),
            'discount' => request('discount'),
            'material_included' => request('material-included') == 'true' ? 1 : 0,
            'status' => 0,
        ]);

        if(request('service') != '') {
            foreach(request('service') as $service) {
                $service = Service::create([
                    'form_id' => $quotation->id,
                    'form_type' => 'App\Quotation',
                    'text' => $service['text'],
                    'price' => $service['price'],
                    'linebreak' => isset($service['linebreak']) ? $service['linebreak'] : 0
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
        $quotation->company = request('company');
        $quotation->address_line_1 = request('address_line_1');
        $quotation->address_line_2 = request('address_line_2');
        $quotation->email = request('email');
        $quotation->tel = request('tel');
        $quotation->note = request('note');
        $quotation->discount = request('discount');
        $quotation->material_included = request('material-included') == 'true' ? 1 : 0;
        $quotation->update();

        DB::table('services')->where('form_id', $quotation->id)->where('form_type', 'App\Quotation')->delete();
        DB::table('materials')->where('form_id', $quotation->id)->where('form_type', 'App\Quotation')->delete();

        dd(request('service'));
        if(request('service') != '') {
            foreach(request('service') as $service) {
                $service = Service::create([
                    'form_id' => $quotation->id,
                    'form_type' => 'App\Quotation',
                    'text' => $service['text'],
                    'price' => $service['price'],
                    'linebreak' => isset($service['linebreak']) ? $service['linebreak'] : 0
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

        $materialTotal = 0;
        $materials = request()->has('material') ? request('material') : [];
        if(request('material-included') == 'false'){
            foreach($materials as $material){
                $materialTotal += ($material['price'] * $material['quantity']);
            }
            $total += $materialTotal;
        }

        $data = [
            'date' => request('date') ? Carbon::createFromFormat('m/d/Y', request('date'))->format('d F, Y') : '',
            'customer' => request('customer'),
            'company' => request('company'),
            'title' => request('title'),
            'address1' => request('address_line_1') ? request('address_line_1') : '',
            'address2' => request('address_line_2') ? request('address_line_2') : '',
            'email' => request('email') ? request('email') : '',
            'tel' => request('tel') ? request('tel') : '',
            'note' => request('note') ? request('note') : '',
            'services' => request('service') ? request('service') : [],
            'materials' => request('material') ? request('material') : [],
            'materialTotal' => $materialTotal == 0 ? '' : number_format($materialTotal, 2),
            'total' => number_format($total, 2),
            'discount' => request('discount') ? number_format(request('discount'), 2) : null,
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
            'company' => $quotation->company,
            'title' => $quotation->title,
            'address1' => $quotation->address_line_1 ? $quotation->address_line_1 : '',
            'address2' => $quotation->address_line_2 ? $quotation->address_line_2 : '',
            'email' => $quotation->email ? $quotation->email : '',
            'tel' => $quotation->tel ? $quotation->tel : '',
            'note' => $quotation->note ? $quotation->note : '',
            'services' => $quotation->services ? $quotation->services->toArray() : [],
            'materials' => $quotation->materials ? $quotation->materials->toArray() : [],
            'materialTotal' => !$quotation->material_included ? number_format($quotation->materialTotal(),2) : '',
            'total' => number_format($quotation->total(), 2),
            'discount' => $quotation->discount ? number_format($quotation->discount, 2) : null,
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
            'company' => $quotation->company,
            'title' => $quotation->title,
            'address1' => $quotation->address_line_1 ? $quotation->address_line_1 : '',
            'address2' => $quotation->address_line_2 ? $quotation->address_line_2 : '',
            'email' => $quotation->email ? $quotation->email : '',
            'tel' => $quotation->tel ? $quotation->tel : '',
            'note' => $quotation->note ? $quotation->note : '',
            'services' => $quotation->services ? $quotation->services->toArray() : [],
            'materials' => $quotation->materials ? $quotation->materials->toArray() : [],
            'materialTotal' => !$quotation->material_included ? $quotation->materialTotal() : '',
            'total' => number_format($quotation->total(), 2),
            'discount' => $quotation->discount ? number_format($quotation->discount, 2) : null,
            'print' => 1,
        ];
        $pdf = PDF::loadView('admin.quotation.pdf', $data);
        return $pdf->stream('quotation.pdf');
    }
}
