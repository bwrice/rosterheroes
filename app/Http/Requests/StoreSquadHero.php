<?php

namespace App\Http\Requests;

use App\Squad;
use App\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreSquadHero extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $squad = Squad::uuidOrFail($this->route('squadUuid'));
        return $this->user()->can('addHero', $squad);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|alpha_dash|between:4,24',
            'race' => 'required|exists:hero_races,name',
            'class' => 'required|exists:hero_classes,name'
        ];
    }
}
