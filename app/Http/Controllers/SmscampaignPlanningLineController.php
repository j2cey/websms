<?php

namespace App\Http\Controllers;

use App\Smscampaign;
use App\SmscampaignPlanningLine;
use App\SmstreatmentResult;
use Illuminate\Http\Request;

class SmscampaignPlanningLineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $recherche_cols = ['id', 'title', 'sendername', 'descript'];

        $sortBy = 'id';
        $orderBy = 'asc';
        $perPage = 50;
        $dt_deb = null;
        $dt_fin = null;
        $treatmentresult = null;
        $campaign_id = null;

        //if ($request->has('orderBy')) $orderBy = $request->query('orderBy');
        //if ($request->has('sortBy')) $sortBy = $request->query('sortBy');
        if ($request->has('perPage')) $perPage = $request->query('perPage');

        if ($request->has('campaign_id')) $campaign_id = $request->query('campaign_id');
        if ($request->has('treatmentresult')) $treatmentresult = $request->query('treatmentresult');

        if ($request->has('dt_deb')) $dt_deb = $request->query('dt_deb');
        if ($request->has('dt_fin')) $dt_fin = $request->query('dt_fin');

        $listvalues = SmscampaignPlanningLine::search($campaign_id,$treatmentresult,$dt_deb,$dt_fin)
            ->with('receiver')
            ->orderBy('id','desc')->paginate($perPage);

        if (is_null($campaign_id)) {
            $campaign = null;
        } else {
            $campaign = Smscampaign::where('id', $campaign_id)->first();
        }

        if (is_null($treatmentresult)) {
            $treatmentresult = SmstreatmentResult::where('titre', 'x@gsf sgfscfs')->pluck('titre', 'code');
        } else {
            $treatmentresult = SmstreatmentResult::whereIn('code', $treatmentresult)->pluck('titre', 'code');
        }

        return view('smscampaignplanninglines.index', compact('campaign','treatmentresult', 'dt_deb', 'dt_fin', 'listvalues', 'perPage'));
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
     * @param  \App\SmscampaignPlanningLine  $smscampaignplanningline
     * @return \Illuminate\Http\Response
     */
    public function show(SmscampaignPlanningLine $smscampaignplanningline)
    {
        $planningline = SmscampaignPlanningLine::with('receiver')
            ->where('id', $smscampaignplanningline->id)->first();
        $report = json_decode($planningline->report);
        $smscampaign = $planningline->planning->campaign;

        return view('smscampaignplanninglines.show',
            ['planningline' => $planningline, 'report' => $report, 'smscampaign' => $smscampaign]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SmscampaignPlanningLine  $smscampaignPlanningResult
     * @return \Illuminate\Http\Response
     */
    public function edit(SmscampaignPlanningLine $smscampaignPlanningResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SmscampaignPlanningLine  $smscampaignPlanningResult
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SmscampaignPlanningLine $smscampaignPlanningResult)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SmscampaignPlanningLine  $smscampaignPlanningResult
     * @return \Illuminate\Http\Response
     */
    public function destroy(SmscampaignPlanningLine $smscampaignPlanningResult)
    {
        //
    }
}
