<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBookRequest extends FormRequest
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
            'title' => 'required',
            'author_name' => 'required',
            'genre' => 'required',
            'description' => 'required',
            'isbn' => 'required|numeric|digits:13|unique:books,isbn',
            'image' => 'required',
            'published_date' => 'required|date',
            'publisher_name' => 'required',
            'status' => 'required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $return = response()->json([
            'status' => 'VALIDATION',
            'code' => 3,
            'data' => $errors->messages(),
            'message' => 'The data given was Invalid'
        ], 200);

        throw new HttpResponseException($return);
    }
}
