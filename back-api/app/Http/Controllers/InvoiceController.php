<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function getBySignature($signatureId)
    {
        $signatures = Invoice::where('signature_id', '=', $signatureId)->orderBy('due_date', 'ASC')->get();

        return response()->json([$signatures], 200);
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

        return response()->json([$invoice], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
