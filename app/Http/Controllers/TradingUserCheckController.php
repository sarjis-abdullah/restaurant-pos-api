<?php

namespace App\Http\Controllers;

use App\Http\Requests\TradingUserCheck\CreateIFCheckRequest;
use App\Http\Requests\TradingUserCheck\CreateKYCCheckRequest;
use App\Http\Requests\TradingUserCheck\CreatePoRCheckRequest;
use App\Services\Trading\Contracts\TradingUser;

class TradingUserCheckController extends Controller
{
    /**
     * @var TradingUser
     */
    private $tradingUser;

    public function __construct(TradingUser $tradingUser)
    {
        $this->tradingUser = $tradingUser;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createKYCCheck($tradingUserId, CreateKYCCheckRequest $request)
    {
        return $this->tradingUser->createKycCheck($tradingUserId, $request->all());
    }

    public function createInstrumentFitCheck($tradingUserId, CreateIFCheckRequest $request)
    {
        return $this->tradingUser->createKycCheck($tradingUserId, $request->all());
    }

    public function createProofOfResidencyCheck($tradingUserId, CreatePoRCheckRequest $request)
    {
        return $this->tradingUser->createKycCheck($tradingUserId, $request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
