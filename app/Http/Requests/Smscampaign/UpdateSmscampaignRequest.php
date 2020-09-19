<?php

namespace App\Http\Requests\Smscampaign;

use App\Smscampaign;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSmscampaignRequest extends FormRequest
{
    use SmscampaignRequestTrait;
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
}
