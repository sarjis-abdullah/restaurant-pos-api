<?php

namespace App\Http\Controllers;

use App\Http\Requests\Instrument\IndexRequest;
use App\Http\Resources\InstrumentSupportedResource;
use App\Http\Resources\InstrumentSupportedResourceCollection;
use App\Models\InstrumentSupported;
use App\Repositories\Contracts\InstrumentSupportedRepository;
use App\Services\Trading\Contracts\TradingInstrument;
use App\Services\Trading\FondService;

class InstrumentController extends Controller
{
    /**
     * @var TradingInstrument
     */
    private $tradingInstrument;

    /**
     * @var FondService
     */
    private $fondService;

    /**
     * @var InstrumentSupportedRepository
     */
    private $instrumentSupportedRepository;

    public function __construct(TradingInstrument $tradingInstrument, FondService $fondService, InstrumentSupportedRepository $instrumentSupportedRepository)
    {
        $this->tradingInstrument = $tradingInstrument;
        $this->fondService = $fondService;
        $this->instrumentSupportedRepository = $instrumentSupportedRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return $this->tradingInstrument->getInstruments($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $isin)
    {
        // todo: convert to repository
        $instrument = $this->instrumentSupportedRepository->findOneBy(['isin' => $isin]);

        if (!$instrument) {
            return response()->json(['message' => 'Instrument not found'], 404);
        }

        $fondServiceData = $this->fondService->getInstrumentDetails($isin);

        return response()->json(array_merge((new InstrumentSupportedResource($instrument))->toArray(request()), ['edisoft' => $fondServiceData]), 200);

    }

    /**
     * Display a listing of the resource.
     */
    public function getListOfSupportedInstruments()
    {
        $instruments = $this->instrumentSupportedRepository->findBy();

        return new InstrumentSupportedResourceCollection($instruments);
    }
}
