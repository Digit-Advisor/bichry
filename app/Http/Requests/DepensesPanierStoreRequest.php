<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepensesPanierStoreRequest extends FormRequest
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
        return [
            'clients_entreprise_id' => 'required|exists:clients_entreprises,id',
            'depense_id' => 'required|exists:depenses,id',
        ];
    }
}
