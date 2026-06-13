<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\DanaTerlindungi;
use Illuminate\Http\Request;

class DanaTerlindungiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dana = DanaTerlindungi::where('user_id', $request->user()->id)
        ->with('account')
        ->get();

        $total = $dana->sum('nominal');

        return response([
            'data' => $dana,
            'total' => $total,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     $request->validate([
        'account_id' => 'required|exists:accounts,id',
        'nominal' => 'required|numeric|min:1',
     ]);

     $account = Account::where('id', $request->account_id)
     ->where('user_id', $request->user()->id)
     ->firstOrFail();

    //  Cek saldo cukup
    if($account->saldo < $request->nominal) {
        return response([
            'message' => 'Saldo rekening tidak cukup',
        ], 422);
    }

    // Potong saldo rekening
    $account->decrement('saldo', $request->nominal);

    // SImpan dana terlindungi
    $dana = DanaTerlindungi::create([
        'user_id' => $request->user()->id,
        'account_id' => $request->account_id,
        'nominal' => $request->nominal,
    ]);

    return response([
        'message' => 'Dana terlindungi berhasil ditambahkan',
        'data' => $dana->load('account'),
    ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
