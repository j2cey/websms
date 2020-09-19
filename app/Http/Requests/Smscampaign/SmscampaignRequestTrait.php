<?php


namespace App\Http\Requests\Smscampaign;


trait SmscampaignRequestTrait
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        /*if ($this->has('type')) {
            if (! is_null($this->input('type'))) {
                $campaigntype = json_decode($this->input('type'), true);
                $this->request->set('type', $campaigntype['code']);
            }
        }*/
        /*$fichier_destinataires = 0;
        if ($this->hasFile('fichier_destinataires')) {
            $fichier_destinataires = 1;
        }
        $this->merge([
            'fichier_destinataires' => $fichier_destinataires,
        ]);*/

        /*if ($this->has('separateur_colonnes')) {
            if (is_null($this->input('separateur_colonnes'))) {
                $this->request->remove('separateur_colonnes');
            }
        }*/
    }
}
