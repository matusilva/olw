<?php

namespace App\Http\Controllers;

use App\Http\Requests\BeerRequest;
use App\Jobs\ExportJob;
use App\Jobs\SendExportEmailJob;
use App\Jobs\StoreExportDataJob;
use App\Models\Meal;
use App\Services\PunkapiService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BeerController extends Controller
{
    public function index(BeerRequest $request, PunkapiService $service)
    {
        $filters = $request->validated();
        $beers = $service->getBeers(...$filters);
        $meals = Meal::all();

        return Inertia::render('Beers', [
            'beers' => $beers,
            'meals' => $meals,
            'filters' => $request->validated()
        ]);
    }

    public function export(BeerRequest $request, PunkapiService $service)
    {
        $dateFormat = now()->format('Y-m-d - H_i');
        $filename = "cervejas-encontradas-$dateFormat-export.xlsx";

        ExportJob::withChain([
            new SendExportEmailJob(Auth::user(), $filename),
            new StoreExportDataJob(Auth::user(), $filename)
        ])->dispatch($request->validated(), $filename);

        return redirect()->back()->with('success', 'Seu arquivo foi enviado para processamento e em breve estará em seu email');
    }
}
