<div class="form-group row {{ $errors->has('titre') ? 'has-error' : '' }}">
    <label class="col-sm-2 col-form-label" for="titre">Titre</label>
    <div class="col-sm-10">
        <input name="titre" type="text" class="form-control" placeholder="Titre" value="{{ old('titre', $smscampaign->titre ?? '') }}"/>
        <small class="text-danger">{{ $errors->has('titre') ? $errors->first('titre') : '' }}</small>
    </div>
</div>

<div class="form-group row {{ $errors->has('smscampaign_type_id') ? 'has-error' : '' }}">
    <label class="col-sm-2 col-form-label"for="smscampaign_type_id">Type</label>
    <div class="col-sm-10">
        <select name="smscampaign_type_id" class="smscampaign_type_id form-control" id="smscampaign_type_id" required>
            @foreach($smscampaign_types as $id => $display)
                <option value="{{ $id }}" {{ (isset($smscampaign->smscampaign_type_id) && $id === $smscampaign->smscampaign_type_id) ? 'selected' : '' }}>{{ $display }}</option>
            @endforeach
        </select>
        <small class="text-danger">{{ $errors->has('smscampaign_type_id') ? $errors->first('smscampaign_type_id') : '' }}</small>
    </div>
</div>

<div class="form-group row {{ $errors->has('expediteur') ? 'has-error' : '' }}">
    <label class="col-sm-2 col-form-label" for="expediteur">Expediteur</label>
    <div class="col-sm-10">
        <input name="expediteur" type="text" class="form-control" placeholder="Expediteur" value="{{  old('expediteur', $smscampaign->expediteur ?? '') }}"/>
        <small class="text-danger">{{ $errors->has('expediteur') ? $errors->first('expediteur') : '' }}</small>
    </div>
</div>

<div class="form-group row {{ $errors->has('message') ? 'has-error' : '' }}">
    <label class="col-sm-2 col-form-label" for="msg">Message</label>
    <div class="col-sm-10">
        <input name="message" type="text" class="form-control" placeholder="Message" value="{{  old('msg', $smscampaign->message ?? '') }}"/>
        <small class="text-danger">{{ $errors->has('message') ? $errors->first('message') : '' }}</small>
    </div>
</div>

<div class="form-group row {{ $errors->has('date_planification') ? 'has-error' : '' }}">
    <label class="col-sm-2 col-form-label" for="date_planification">Date Planification</label>
    <div class="col-sm-10">
        <div class="input-group">
            <input name="date_planification" type="text" class="form-control" placeholder="dd/mm/yyyy" id="datepicker-autoclose" value="{{ old('date_planification', $smscampaign->date_planification ?? $nowdate) }}" >
            <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
        </div>
        <small class="text-danger">{{ $errors->has('date_planification') ? $errors->first('date_planification') : '' }}</small>
    </div>
</div>

<div class="form-group row {{ $errors->has('description') ? 'has-error' : '' }}">
    <label class="col-sm-2 col-form-label" for="description">Description</label>
    <div class="col-sm-10">
        <input name="description" type="text" class="form-control" placeholder="Description" value="{{  old('description', $smscampaign->description ?? '') }}"/>
        <small class="text-danger">{{ $errors->has('description') ? $errors->first('description') : '' }}</small>
    </div>
</div>

<div class="form-group row {{ $errors->has('premiere_ligne_entete') ? 'has-error' : '' }}">
    <label class="col-sm-2 col-form-label"for="premiere_ligne_entete">En-tete de colonne ?</label>
    <div class="col-sm-10">
        <input type="checkbox" name="premiere_ligne_entete" class="switch-input" value="1" />
        <small class="text-danger">{{ $errors->has('premiere_ligne_entete') ? $errors->first('premiere_ligne_entete') : '' }}</small>
    </div>
</div>

<div class="form-group row {{ $errors->has('separateur_colonnes') ? 'has-error' : '' }}">
    <label class="col-sm-2 col-form-label" for="separateur_colonnes">Séparateur Colonnes</label>
    <div class="col-sm-10">
        <input name="separateur_colonnes" type="text" class="form-control" placeholder="Séparateur Colonnes" value="{{  old('separateur_colonnes', $smscampaign->separateur_colonnes ?? '') }}"/>
        <small class="text-danger">{{ $errors->has('separateur_colonnes') ? $errors->first('separateur_colonnes') : '' }}</small>
    </div>
</div>

<div class="form-group row {{ $errors->has('fichier_destinataires') ? 'has-error' : '' }}">
    <label class="col-sm-2 col-form-label" for="fichier_destinataires">Fichier Destinataires/Message</label>
    <div class="col-sm-10">
        <input type="file" name="fichier_destinataires" id="fichier_destinataires" class="form-control border-input"/>
        <small class="text-danger">{{ $errors->has('fichier_destinataires') ? $errors->first('fichier_destinataires') : '' }}</small>
    </div>
</div>

@csrf
