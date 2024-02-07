<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Artist\IndexRequest;
use App\Http\Resources\Api\V1\ArtistResource;
use App\Models\Artist;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ArtistController extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('cache.response:60')->only('index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request): ResourceCollection
    {
        return ArtistResource::collection(QueryBuilder::for(Artist::class)
            ->allowedFilters([
                AllowedFilter::exact('active'),
                AllowedFilter::partial('email'),
            ])
            ->paginate(100)
            ->withQueryString()
        );
    }
}
