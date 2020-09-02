<?php

namespace App\Http\Controllers;

use App\Smscampaign;
use App\SmscampaignFile;
use App\SmscampaignPlanning;
use App\SmscampaignStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Traits\SmsImportFileTrait;
use Illuminate\Support\Facades\DB;

class SmscampaignController extends Controller
{
    use SmsImportFileTrait;

    public function testfunction(){
        //Get number of lines
        $smscampaign_file = SmscampaignFile::find(6);
        $pendingfiles_dir = config('app.smscampaigns_filesfolder');
        $command_to_exec = "wc -l '".$pendingfiles_dir."/"."$smscampaign_file->name'";
        $totalLines = intval(exec($command_to_exec));
        dd($command_to_exec,$totalLines);
    }

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
        $title = null;
        $sendername = null;
        $descript = null;
        $dt_deb = null;
        $dt_fin = null;
        $campstatus = null;

        //if ($request->has('orderBy')) $orderBy = $request->query('orderBy');
        //if ($request->has('sortBy')) $sortBy = $request->query('sortBy');
        if ($request->has('perPage')) $perPage = $request->query('perPage');

        if ($request->has('title')) $title = $request->query('title');
        if ($request->has('sendername')) $sendername = $request->query('sendername');
        if ($request->has('descript')) $descript = $request->query('descript');

        if ($request->has('dt_deb')) $dt_deb = $request->query('dt_deb');
        if ($request->has('dt_fin')) $dt_fin = $request->query('dt_fin');

        //dd($request, $seltypds,$dt_deb,$dt_fin);
        $listvalues = Smscampaign::search($title,$sendername,$campstatus,$descript,$dt_deb,$dt_fin)
            ->with('status')
            ->orderBy('id','desc')->paginate($perPage);

        if (is_null($campstatus)) {
            $campstatus = SmscampaignStatus::where('titre', 'x@gsf sgfscfs')->pluck('titre', 'id');
        } else {
            $campstatus = SmscampaignStatus::whereIn('id', $campstatus)->pluck('titre', 'id');
        }

        return view('smscampaigns.index', compact('title', 'sendername', 'campstatus', 'descript', 'dt_deb', 'dt_fin', 'listvalues', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $smscampaign = new Smscampaign();
        $smscampaign_types = DB::table('smscampaign_types')->get()->pluck('titre', 'id');

        $nowdate = Carbon::now();

        return view('smscampaigns.create')
            ->with('nowdate', $nowdate)
            ->with('smscampaign_types', $smscampaign_types)
            ->with('smscampaign', $smscampaign);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'fichier_destinataires' => 'required|mimes:csv,txt',
            'titre' => 'required',
            'smscampaign_type_id' => 'required',
            'expediteur' => 'required',
            'separateur_colonnes' => 'required',
        ]);

        //get file from upload
        $path = request()->file('fichier_destinataires')->getRealPath();

        //turn into array
        $file = file($path);

        if ($request->has('premiere_ligne_entete')) {
            //remove first line
            $data = array_slice($file, 1);
        } else {
            $data = $file;
        }

        $formInput = $request->all();

        $new_smscampaign = Smscampaign::create([
            'titre' => $formInput['titre'],
            'expediteur' => $formInput['expediteur'],
            'message' => $formInput['message'],
            'description' => $formInput['description'],
            'separateur_colonnes' => $formInput['separateur_colonnes'],
            'messages_individuels' => array_key_exists('messages_individuels', $formInput),
            'smscampaign_status_id' => SmscampaignStatus::coded("1")->first()->id,
            'smscampaign_type_id' => $formInput['smscampaign_type_id'],
        ]);

        // Nouveau planning (en attente importation fichiers)
        $new_planning = SmscampaignPlanning::create([
            'plan_at' => Carbon::now(), // TODO: récupérer la date de planification
            'smscampaign_id' => $new_smscampaign->id,
            'smscampaign_status_id' => SmscampaignStatus::coded("1")->first()->id,
            'stat_all' => 0,
            'current' => true,
        ]);

        //loop through file and split every 1000 lines
        $file_max_line = 500;
        $parts = (array_chunk($data, $file_max_line));
        $parts_count = count($parts);
        $i = 1;

        $pendingfiles_dir = config('app.smscampaigns_filesfolder');
        foreach($parts as $line) {
            $filename = $new_smscampaign->id.'_'.date('y-m-d-H-i-s').'_'.$i.'.csv';
            $filename_full = $pendingfiles_dir.'/'.$filename;

            file_put_contents($filename_full, $line);
            $i++;

            $nb_rows = intval(exec("wc -l '".$filename_full."'"));
            $new_file = SmscampaignFile::create([
                'name' => $filename,
                'smscampaign_planning_id' => $new_planning->id,
                'nb_rows' => $nb_rows,
                'smscampaign_status_id' => SmscampaignStatus::coded("1")->first()->id,
                'import_report' => json_encode([]),
            ]);
        }
        //$new_planning->setStatus();

        // Sessions Message
        $request->session()->flash('msg_success',"Campagne créée avec Succès.");

        return redirect()->action('SmscampaignController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Smscampaign  $smscampaign
     * @return \Illuminate\Http\Response
     */
    public function show(Smscampaign $smscampaign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Smscampaign  $smscampaign
     * @return \Illuminate\Http\Response
     */
    public function edit(Smscampaign $smscampaign)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Smscampaign  $smscampaign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Smscampaign $smscampaign)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Smscampaign  $smscampaign
     * @return \Illuminate\Http\Response
     */
    public function destroy(Smscampaign $smscampaign)
    {
        //
    }

    public function importcampaignfiles() {
        $files_to_import = SmscampaignFile::whereNull('imported_at')->get();
        foreach ($files_to_import as $file) {
            $this->importFile($file);
        }
        dd($files_to_import);
    }
}
