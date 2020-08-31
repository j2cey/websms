<?php

namespace App\Http\Controllers;

use App\SmscampaignStatus;
use Illuminate\Http\Request;

class SmscampaignStatusController extends Controller
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
     * @param  \App\SmscampaignStatus  $smscampaignStatus
     * @return \Illuminate\Http\Response
     */
    public function show(SmscampaignStatus $smscampaignStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SmscampaignStatus  $smscampaignStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(SmscampaignStatus $smscampaignStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SmscampaignStatus  $smscampaignStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SmscampaignStatus $smscampaignStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SmscampaignStatus  $smscampaignStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(SmscampaignStatus $smscampaignStatus)
    {
        //
    }

    public function selectmorecampaignstatuses(Request $request)
    {
        $search = $request->get('search');
        $data = SmscampaignStatus::select(['id', 'titre'])
            ->where('titre', 'like', '%' . $search . '%')->orderBy('titre')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}
