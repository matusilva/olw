<?php

namespace App\Http\Controllers;

use App\Http\Requests\BeerRequest;
use App\Jobs\ExportJob;
use App\Jobs\SendExportEmailJob;
use App\Jobs\StoreExportDataJob;
use App\Services\PunkapiService;
use Illuminate\Support\Facades\Auth;

class BeerController extends Controller
{
    public function index(BeerRequest $request, PunkapiService $service)
    {
        return $service->getBeers(...$request->validated());
    }

    public function export(BeerRequest $request, PunkapiService $service)
    {
        $dateFormat = now()->format('Y-m-d - H_i');
        $filename = "cervejas-encontradas-$dateFormat-export.xlsx";

        ExportJob::withChain([
            new SendExportEmailJob(Auth::user(), $filename),
            new StoreExportDataJob(Auth::user(), $filename)
        ])->dispatch($request->validated(), $filename);

        return 'relatório gerado';
    }
}
