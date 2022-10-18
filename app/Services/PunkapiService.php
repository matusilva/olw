<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PunkapiService
{
    public function getBeers()
    {
        return Http::punkapi()
            ->get('/beers')
            ->throw()
            ->json();
    }
}
