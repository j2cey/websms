<?php

namespace App\Http\Controllers;

use App\SmssendStatus;
use Illuminate\Http\Request;

class SmssendStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SmssendStatus  $smssendStatus
     * @return \Illuminate\Http\Response
     */
    public function show(SmssendStatus $smssendStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SmssendStatus  $smssendStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(SmssendStatus $smssendStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SmssendStatus  $smssendStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SmssendStatus $smssendStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SmssendStatus  $smssendStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(SmssendStatus $smssendStatus)
    {
        //
    }

    public function selectmoresendstatuses(Request $request)
    {
        $search = $request->get('search');
        $data = SmssendStatus::select(['id', 'titre'])
            ->where('titre', 'like', '%' . $search . '%')->orderBy('titre')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}
