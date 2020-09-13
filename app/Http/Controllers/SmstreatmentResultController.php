<?php

namespace App\Http\Controllers;

use App\SmstreatmentResult;
use Illuminate\Http\Request;

class SmstreatmentResultController extends Controller
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
     * @param  \App\SmstreatmentResult  $smstreatmentResult
     * @return \Illuminate\Http\Response
     */
    public function show(SmstreatmentResult $smstreatmentResult)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SmstreatmentResult  $smstreatmentResult
     * @return \Illuminate\Http\Response
     */
    public function edit(SmstreatmentResult $smstreatmentResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SmstreatmentResult  $smstreatmentResult
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SmstreatmentResult $smstreatmentResult)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SmstreatmentResult  $smstreatmentResult
     * @return \Illuminate\Http\Response
     */
    public function destroy(SmstreatmentResult $smstreatmentResult)
    {
        //
    }

    public function selectmoretreatmentresults(Request $request)
    {
        $search = $request->get('search');
        $data = SmstreatmentResult::select(['code', 'titre'])
            ->where('titre', 'like', '%' . $search . '%')->orderBy('titre')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}
