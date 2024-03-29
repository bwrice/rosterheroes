<?php

namespace App\Http\Requests;

use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Policies\SquadPolicy;
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
        $squad = Squad::findUuid($this->route('squadUuid'));
        if (! $squad) {
            throw ValidationException::withMessages([
                'Squad could not be found'
            ]);
        }
        return $this->user()->can(SquadPolicy::MANAGE, $squad);
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
