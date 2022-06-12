<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\EditTabRequest;
use App\Models\Tab;
use Illuminate\Http\Request;

class TabController extends BaseController
{
    public function options()
    {
        return $this->sendResponse([
        ], 'OK');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $tabs = Tab::orderBy('id', 'asc');
        $searchQuery = $request->get('query');
        if (strlen($searchQuery)) {
            $tabs->where('title_ru', "like", "%$searchQuery%");
        }
        return $this->sendResponse($tabs->paginate(config('app.pagesize', 20)), 'success');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EditTabRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EditTabRequest $request)
    {
        $tab = new Tab($request->validated());
        $tab->save();
        return $this->sendResponse([
            'item' => $tab,
        ], 'OK');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Tab $tab)
    {
        return $this->sendResponse($tab, 'success');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\EditTabRequest $request
     * @param \App\Models\Tab $tab
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EditTabRequest $request, Tab $tab)
    {
        $tab->update($request->validated());
        return $this->sendResponse(true, 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Tab $tab
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Tab $tab)
    {
        $tab->delete();
        return $this->sendResponse(true, 'success');
    }
}
