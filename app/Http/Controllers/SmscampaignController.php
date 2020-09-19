<?php

namespace App\Http\Controllers;

use App\Http\Requests\Smscampaign\CreateSmscampaignRequest;
use App\Http\Requests\Smscampaign\UpdateSmscampaignRequest;
use App\Smscampaign;
use App\SmscampaignFile;
use App\SmscampaignType;
use App\SmsimportStatus;
use App\SmssendStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Traits\SmsImportFileTrait;
use Illuminate\Support\Facades\DB;

class SmscampaignController extends Controller
{
    use SmsImportFileTrait;

    public function testfunction(){

        dd( str_replace(['-',' ',':'],"",gmdate('Y-m-d h:i:s')) );

        $os = array("Mac", "NT", "Irix", "Linux");
        if (in_array("Irix", $os)) {
            echo "Got Irix";
        }
        if (in_array("mac", $os)) {
            echo "Got mac";
        }
        dd("Fin Test");
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
        return Smscampaign::all();

        $recherche_cols = ['id', 'title', 'sendername', 'descript'];

        $sortBy = 'id';
        $orderBy = 'asc';
        $perPage = 50;
        $title = null;
        $sendername = null;
        $descript = null;
        $dt_deb = null;
        $dt_fin = null;
        $importstatus = null;
        $sendstatus = null;

        //if ($request->has('orderBy')) $orderBy = $request->query('orderBy');
        //if ($request->has('sortBy')) $sortBy = $request->query('sortBy');
        if ($request->has('perPage')) $perPage = $request->query('perPage');

        if ($request->has('title')) $title = $request->query('title');
        if ($request->has('sendername')) $sendername = $request->query('sendername');
        if ($request->has('descript')) $descript = $request->query('descript');

        if ($request->has('dt_deb')) $dt_deb = $request->query('dt_deb');
        if ($request->has('dt_fin')) $dt_fin = $request->query('dt_fin');

        //dd($request, $seltypds,$dt_deb,$dt_fin);
        $listvalues = Smscampaign::search($title,$sendername,$importstatus,$sendstatus,$descript,$dt_deb,$dt_fin)
            ->with('importstatus')->with('sendstatus')
            ->orderBy('id','desc')->paginate($perPage);

        if (is_null($importstatus)) {
            $importstatus = SmsimportStatus::where('titre', 'x@gsf sgfscfs')->pluck('titre', 'id');
        } else {
            $importstatus = SmsimportStatus::whereIn('id', $importstatus)->pluck('titre', 'id');
        }

        if (is_null($sendstatus)) {
            $sendstatus = SmssendStatus::where('titre', 'x@gsf sgfscfs')->pluck('titre', 'id');
        } else {
            $sendstatus = SmssendStatus::whereIn('id', $sendstatus)->pluck('titre', 'id');
        }

        return view('smscampaigns.index', compact('title', 'sendername', 'importstatus', 'sendstatus', 'descript', 'dt_deb', 'dt_fin', 'listvalues', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $smscampaign = new Smscampaign();
        $smscampaign_types = DB::table('smscampaign_types')->get()->pluck('titre', 'code');

        $nowdate = Carbon::now();

        return view('smscampaigns.create')
            ->with('nowdate', $nowdate)
            ->with('smscampaign_types', $smscampaign_types)
            ->with('smscampaign', $smscampaign);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateSmscampaignRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateSmscampaignRequest $request)
    {
        $user = auth()->user();

        //dd(request()->file('fichier_destinataires'), $request);

        //get file from upload
        $fullpathfile = request()->file('fichier_destinataires')->getRealPath();

        $formInput = $request->all();

        $new_smscampaign = Smscampaign::create([
            'titre' => $formInput['titre'],
            'expediteur' => $formInput['expediteur'],
            'message' => $formInput['message'],
            'description' => $formInput['description'],
            'separateur_colonnes' => $formInput['separateur_colonnes'],
            'smsimport_status_id' => SmsimportStatus::coded("0")->first()->id,
            'smssend_status_id' => SmssendStatus::coded("0")->first()->id,
            'smscampaign_type_id' => SmscampaignType::coded($formInput['smscampaign_type_code'])->first()->id,
            'user_id' => $user->id,
        ]);

        $new_smscampaign->addFile( $fullpathfile, ($request->has('premiere_ligne_entete')) );

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
        $smscampaign = Smscampaign::with('importstatus')
            ->with('sendstatus')->where('id', $smscampaign->id)->first();
        return view('smscampaigns.show', ['smscampaign' => $smscampaign]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Smscampaign  $smscampaign
     * @return \Illuminate\Http\Response
     */
    public function edit(Smscampaign $smscampaign)
    {
        $smscampaign_types = DB::table('smscampaign_types')->get()->pluck('titre', 'code');

        $nowdate = Carbon::now();

        return view('smscampaigns.edit')
            ->with('nowdate', $nowdate)
            ->with('smscampaign_types', $smscampaign_types)
            ->with('smscampaign', $smscampaign);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSmscampaignRequest $request
     * @param Smscampaign $smscampaign
     */
    public function update(UpdateSmscampaignRequest $request, Smscampaign $smscampaign)
    {
        $user = auth()->user();
        $formInput = $request->all();
        $smscampaign->update([
            'titre' => $formInput['titre'],
            'expediteur' => $formInput['expediteur'],
            'message' => $formInput['message'],
            'description' => $formInput['description'],
            'separateur_colonnes' => $formInput['separateur_colonnes'],
            'smscampaign_type_id' => SmscampaignType::coded($formInput['smscampaign_type_code'])->first()->id,
        ]);

        if ($request->hasFile('fichier_destinataires')) {
            //get file from upload
            $fullpathfile = request()->file('fichier_destinataires')->getRealPath();
            $smscampaign->addFile( $fullpathfile, ($request->has('premiere_ligne_entete')) );
        }

        // Réinitialiser le curseur des fichiers ayant des failed
        $smscampaign->resetFailedFilesCursor();
        // Réinitialiser le curseur des envois ayant des failed
        $smscampaign->resetFailedLinesCursor();

        // Sessions Message
        $request->session()->flash('msg_success',"Campagne '" . $smscampaign->titre . "' modifiée avec Succès.");

        return redirect()->action('SmscampaignController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Smscampaign  $smscampaign
     * @return \Illuminate\Http\Response
     */
    public function destroy(Smscampaign $smscampaign)
    {
        // On suspend d'abord tous les éléments de la campagne
        $smscampaign->suspend();
        // Puis on la supprime (soft)
        $smscampaign->delete();
        session()->flash('msg_success',"Campagne '" . $smscampaign->titre . "' supprimée avec Succès.");

        return redirect()->action('SmscampaignController@index');
    }

    public function importcampaignfiles() {
        $files_to_import = SmscampaignFile::whereNull('imported_at')->get();
        foreach ($files_to_import as $file) {
            $this->importFile($file);
        }
        dd($files_to_import);
    }
}
