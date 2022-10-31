<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Signatures;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;

class SignatureController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($userId, $clubId)
    {
        $user = User::find($userId);
        $user->clubs()->attach($clubId, ['status' => 'active']);

        $signature = Signatures::where('user_id', '=', $userId)->where('club_id', '=', $clubId)->first();

        // Create the invoices
        $date = new DateTime();
        for ($i = 0; $i < 12; $i++) {
            $date->modify('+1 month'); // Increments one month from the current date

            $invoice = new Invoice();
            $invoice->signature_id = $signature->id;
            $invoice->payment_status = 'unpaid';
            $invoice->due_date = $date;
            $invoice->save();
        }

        return response()->json([$signature], 200);
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
