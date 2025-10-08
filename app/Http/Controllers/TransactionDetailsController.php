<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetails;
use App\Http\Requests\StoreTransactionDetailsRequest;
use App\Http\Requests\UpdateTransactionDetailsRequest;

class TransactionDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionDetailsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionDetails $transactionDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransactionDetails $transactionDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionDetailsRequest $request, TransactionDetails $transactionDetails)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionDetails $transactionDetails)
    {
        //
    }
}
