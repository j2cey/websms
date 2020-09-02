<?php

namespace App\Http\Controllers;

use App\SmsimportStatus;
use Illuminate\Http\Request;

class SmsimportStatusController extends Controller
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
     * @param  \App\SmsimportStatus  $smsimportStatus
     * @return \Illuminate\Http\Response
     */
    public function show(SmsimportStatus $smsimportStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SmsimportStatus  $smsimportStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(SmsimportStatus $smsimportStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SmsimportStatus  $smsimportStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SmsimportStatus $smsimportStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SmsimportStatus  $smsimportStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(SmsimportStatus $smsimportStatus)
    {
        //
    }

    public function selectmoreimportstatuses(Request $request)
    {
        $search = $request->get('search');
        $data = SmsimportStatus::select(['id', 'titre'])
            ->where('titre', 'like', '%' . $search . '%')->orderBy('titre')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}
