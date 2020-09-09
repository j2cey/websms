<?php

namespace App\Http\Requests\Smscampaign;

use App\Smscampaign;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSmscampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Smscampaign::updateRules($this->smscampaign);
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
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
