<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Signatures;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function getBySignature($signatureId)
    {
        $signatures = Invoice::where('signature_id', '=', $signatureId)->orderBy('due_date', 'ASC')->get();

        return response()->json($signatures, 200);
    }

    public function editPayment($id, Request $request)
    {
        $rules = [
            'status' => 'in:paid,unpaid'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $status = $request->input('status');

        $invoice = Invoice::find($id);
        $invoice->payment_status = $status;
        $invoice->save();

        // Checks if all invoices are paid
        //$allInvoices = Invoice::where('signature_id', '=', $invoice->signature_id)->get();
        $allInvoices = Invoice::where('signature_id', '=', $invoice->signature_id)
            ->where('payment_status', '=', 'unpaid')
            ->orderBy('due_date', 'ASC')->get()->count();

        if ($allInvoices == 0) {
            $signature = Signatures::find($invoice->signature_id);
            $signature->status = 'active';
            $signature->save();
        }

        return response()->json($invoice, 200);
    }
}
