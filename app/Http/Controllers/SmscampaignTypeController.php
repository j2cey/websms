<?php

namespace App\Http\Controllers;

use App\SmscampaignType;
use Illuminate\Http\Request;

class SmscampaignTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SmscampaignType::all();
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
     * @param  \App\SmscampaignType  $smscampaignType
     * @return \Illuminate\Http\Response
     */
    public function show(SmscampaignType $smscampaignType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SmscampaignType  $smscampaignType
     * @return \Illuminate\Http\Response
     */
    public function edit(SmscampaignType $smscampaignType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SmscampaignType  $smscampaignType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SmscampaignType $smscampaignType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SmscampaignType  $smscampaignType
     * @return \Illuminate\Http\Response
     */
    public function destroy(SmscampaignType $smscampaignType)
    {
        //
    }
}
