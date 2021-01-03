<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Invoice;
use App\Quotation;
use App\Service;
use App\Material;
use Carbon\Carbon;
use Terbilang;
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
            'company' => request('company'),
            'company_line_1' => request('company_line_1'),
            'company_line_2' => request('company_line_2'),
            'purchase_order' => request('po'),
            'deposit' => request('deposit'),
            'discount' => request('discount'),
            'note' => request('note'),
            'material_included' => request('material-included') == 'true' ? 1 : 0,
        ]);

        if(request('service') != '') {
            foreach(request('service') as $service) {
                $service = Service::create([
                    'form_id' => $invoice->id,
                    'form_type' => 'App\Invoice',
                    'text' => $service['text'],
                    'price' => $service['price'],
                    'linebreak' => $service['linebreak']
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
        $invoice->company = request('company');
        $invoice->company_line_1 = request('company_line_1');
        $invoice->company_line_2 = request('company_line_2');
        $invoice->purchase_order = request('po');
        $invoice->deposit = request('deposit');
        $invoice->discount = request('discount');
        $invoice->note = request('note');
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
        if(request('material-included') == 'true') {
            $materialTotal = 0;
        }
        else if(request('material-included') == 'false' || (!request()->has('material-included') && !$quotation->material_included)){
            foreach($materials as $material){
                $materialTotal += ($material['price'] * $material['quantity']);
            }
            $total += $materialTotal;
        }

        $deposit = request('deposit') ? request('deposit') : 0;
        $priceInText = preg_replace('/,+/', '', Terbilang::make($total - $deposit, ' ONLY'));
        $priceInText = strtoupper($priceInText);
        $data = [
            'id' => 'NEW',
            'date' => request('date') ? request('date') : '',
            'customer' => request('customer') ? request('customer') : $quotation->to,
            'company' => request('company') ? request('company') : $quotation->company,
            'company1' => request('company_line_1') ? request('company_line_1') : $quotation->company_line_1,
            'company2' => request('company_line_2') ? request('company_line_2') : $quotation->company_line_2,
            'po' => request('po') ? request('po') : '',
            'discount' => request('discount') ? request('discount') : $quotation->discount,
            'note' => request('note') ? request('note') : $quotation->note,
            'services' => $services,
            'materials' => $materials,
            'materialTotal' => $materialTotal == 0 ? '' : number_format($materialTotal, 2),
            'total' => $total,
            'deposit' => $deposit,
            'priceInText' => $priceInText
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
            'company' => $invoice->company,
            'company1' => $invoice->company_line_1,
            'company2' => $invoice->company_line_2,
            'po' => $invoice->purchase_order,
            'services' => $invoice->services ? $invoice->services->toArray() : [],
            'materials' => $invoice->materials ? $invoice->materials->toArray() : [],
            'materialTotal' => $invoice->material_included == 1 ? '' : number_format($invoice->materialTotal(), 2),
            'total' => $invoice->total(),
            'deposit' => $invoice->deposit,
            'discount' => $invoice->discount,
            'note' => $invoice->note,
            'priceInText' => $invoice->priceInText(),
            'invoice_id' => $id,
        ];
        // dd(number_format($invoice->total() - $invoice->deposit , 2));

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
            'company' => $invoice->company,
            'company1' => $invoice->company_line_1,
            'company2' => $invoice->company_line_2,
            'po' => $invoice->purchase_order,
            'services' => $invoice->services ? $invoice->services->toArray() : [],
            'materials' => $invoice->materials ? $invoice->materials->toArray() : [],
            'materialTotal' => $invoice->material_included == 1 ? '' : number_format($invoice->materialTotal(), 2),
            'total' => $invoice->total(),
            'deposit' => $invoice->deposit,
            'discount' => $invoice->discount,
            'note' => $invoice->note,
            'priceInText' => $invoice->priceInText(),
            'print' => 1,
        ];
        $pdf = PDF::loadView('admin.invoice.pdf', $data);
        return $pdf->stream('invoice.pdf');
    }
}
