<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasFetchAllRenderCapabilities;
use App\Http\Requests\GenreRequest;
use App\Models\Genre;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GenreController extends Controller
{

    use HasFetchAllRenderCapabilities;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        $this->setGetAllBuilder(Genre::query());
        $this->setGetAllOrdering('name', 'asc');
        $this->parseRequestConditions($request);
        return new ResourceCollection($this->getAll()->paginate());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param GenreRequest $request
     * @return \App\Http\Resources\Genre
     */
    public function store(GenreRequest $request)
    {
        $genre = new Genre($request->validated());
        $genre->save();

        return new \App\Http\Resources\Genre($genre);
    }

    /**
     * Show the resource
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        try {
            $genre = Genre::findOrFail($id);
        } catch (ModelNotFoundException $e){
            return response()->json([], 404);
        }

        return new \App\Http\Resources\Genre($genre);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @param GenreRequest $request
     * @return \App\Http\Resources\Genre
     */
    public function update(GenreRequest $request, $id)
    {
        $genre = Genre::findOrFail($id);

        $genre->fill($request->validated());
        $genre->save();

        return new \App\Http\Resources\Genre($genre);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $genre = Genre::findOrFail($id);

        $genre->delete();

        return response()->json();
    }
}
