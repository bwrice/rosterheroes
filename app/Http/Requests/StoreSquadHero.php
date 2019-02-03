<?php

namespace App\Http\Requests;

use App\Squad;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

/**
 * Class StoreSquadHero
 * @package App\Http\Requests
 * @deprecated
 */
class StoreSquadHero extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $squad = Squad::uuid($this->route('squadUuid'));
        if (! $squad) {
            throw ValidationException::withMessages([
                'Squad could not be found'
            ]);
        }
        return $this->user()->can(Squad::MANAGE_AUTHORIZATION, $squad);
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
