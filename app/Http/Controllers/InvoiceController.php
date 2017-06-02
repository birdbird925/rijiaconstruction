<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Invoice;
use App\Quotation;
use App\Service;
use App\Material;
use Carbon\Carbon;
use PDF;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $invoices = Invoice::orderBy('id', 'DESC')->get();
        return view('admin.invoice.index', compact('invoices'));
    }

    public function create()
    {
        if(!request()->has('quotation')) {return redirect('admin/quotation');}
        $quotation = Quotation::findOrFail(request('quotation'));
        return view('admin.invoice.create', compact('quotation'));
    }

    public function store()
    {
        Quotation::where('id', request('quotation'))->update(['status' => 1]);

        $invoice = Invoice::create([
            'quotation_id' => request('quotation'),
            'date' => Carbon::createFromFormat('m/d/Y', request('date')),
            'to' => request('customer'),
            'company_line_1' => request('company_line_1'),
            'company_line_2' => request('company_line_2'),
            'purchase_order' => request('po'),
            'material_included' => request('material-included') == 'true' ? 1 : 0,
        ]);

        if(request('service') != '') {
            foreach(request('service') as $service) {
                $service = Service::create([
                    'form_id' => $invoice->id,
                    'form_type' => 'App\Invoice',
                    'text' => $service['text'],
                    'price' => $service['price']
                ]);
            }
        }

        if(request('material') != '') {
            foreach(request('material') as $material) {
                $material = Material::create([
                    'form_id' => $invoice->id,
                    'form_type' => 'App\Invoice',
                    'text' => $material['text'],
                    'quantity' => $material['quantity'],
                    'unit' => $material['unit'],
                    'price' => $material['price']
                ]);
            }
        }
        session()->flash('success', 'Invoice create successful!');
        return redirect('/admin/invoice');
    }

    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('admin.invoice.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('admin.invoice.edit', compact('invoice'));
    }

    public function update($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->date = Carbon::createFromFormat('m/d/Y', request('date'));
        $invoice->to = request('customer');
        $invoice->company_line_1 = request('company_line_1');
        $invoice->company_line_2 = request('company_line_2');
        $invoice->purchase_order = request('po');
        $invoice->material_included = request('material-included') == 'true' ? 1 : 0;
        $invoice->update();

        DB::table('services')->where('form_id', $invoice->id)->where('form_type', 'App\Invoice')->delete();
        DB::table('materials')->where('form_id', $invoice->id)->where('form_type', 'App\Invoice')->delete();

        if(request('service') != '') {
            foreach(request('service') as $service) {
                $service = Service::create([
                    'form_id' => $invoice->id,
                    'form_type' => 'App\Invoice',
                    'text' => $service['text'],
                    'price' => $service['price']
                ]);
            }
        }

        if(request('material') != '') {
            foreach(request('material') as $material) {
                $material = Material::create([
                    'form_id' => $invoice->id,
                    'form_type' => 'App\Invoice',
                    'text' => $material['text'],
                    'quantity' => $material['quantity'],
                    'unit' => $material['unit'],
                    'price' => $material['price']
                ]);
            }
        }
        session()->flash('success', 'Invoice update successful!');
        return redirect('/admin/invoice/');
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        Quotation::find($invoice->quotation_id)->update(['status' => 0]);
        DB::table('services')->where('form_id', $invoice->id)->where('form_type', 'App\Invoice')->delete();
        DB::table('materials')->where('form_id', $invoice->id)->where('form_type', 'App\Invoice')->delete();
        $invoice->delete();
        session()->flash('success', 'Quotation delete successful!');
        return redirect('/admin/invoice');
    }

    public function preview()
    {
        if(!request()->has('quotation')) {return redirect('admin/quotation');}
        $quotation = Quotation::findOrFail(request('quotation'));

        $total = 0;
        $services = request()->has('service') ? request('service') : $quotation->services->toArray();
        foreach($services as $service){
            $total += $service['price'];
        }

        $materialTotal = 0;
        $materials = request()->has('material') ? request('material') : $quotation->materials->toArray();
        if(request('material-included') == 'false'){
            foreach($materials as $material){
                $materialTotal += ($material['price'] * $material['quantity']);
            }
            $total += $materialTotal;
        }

        $data = [
            'id' => 'NEW',
            'date' => request('date') ? request('date') : '',
            'customer' => request('customer') ? request('customer') : $quotation->to,
            'company1' => request('company_line_1') ? request('company_line_1') : '',
            'company2' => request('company_line_2') ? request('company_line_2') : '',
            'po' => request('po') ? request('po') : '',
            'services' => $services,
            'materials' => $materials,
            'materialTotal' => $materialTotal == 0 ? '' : number_format($materialTotal, 2),
            'total' => number_format($total, 2),
        ];
        $pdf = PDF::loadView('admin.invoice.pdf', $data);
        return $pdf->stream('inovice.pdf');
    }

    public function pdf($id)
    {
        $invoice = Invoice::findOrFail($id);
        $data = [
            'id' => $invoice->refNumber(),
            'date' => Carbon::createFromFormat('Y-m-d H:i:s', $invoice->date)->format('m/d/Y'),
            'customer' => $invoice->to,
            'company1' => $invoice->company_line_1,
            'company2' => $invoice->company_line_2,
            'po' => $invoice->po,
            'services' => $invoice->services ? $invoice->services->toArray() : [],
            'materials' => $invoice->materials ? $invoice->materials->toArray() : [],
            'materialTotal' => $invoice->material_included == 1 ? '' : number_format($invoice->materialTotal(), 2),
            'total' => number_format($invoice->total(), 2),
        ];
        $pdf = PDF::loadView('admin.invoice.pdf', $data);
        return $pdf->stream('invoice.pdf');
    }

    public function printPDF($id)
    {
        $invoice = Invoice::findOrFail($id);
        $data = [
            'id' => $invoice->refNumber(),
            'date' => Carbon::createFromFormat('Y-m-d H:i:s', $invoice->date)->format('m/d/Y'),
            'customer' => $invoice->to,
            'company1' => $invoice->company_line_1,
            'company2' => $invoice->company_line_2,
            'po' => $invoice->po,
            'services' => $invoice->services ? $invoice->services->toArray() : [],
            'materials' => $invoice->materials ? $invoice->materials->toArray() : [],
            'materialTotal' => $invoice->material_included == 1 ? '' : number_format($invoice->materialTotal(), 2),
            'total' => number_format($invoice->total(), 2),
            'print' => 1,
        ];
        $pdf = PDF::loadView('admin.invoice.pdf', $data);
        return $pdf->stream('invoice.pdf');
    }
}
