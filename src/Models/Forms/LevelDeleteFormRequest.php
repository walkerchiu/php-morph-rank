<?php

namespace WalkerChiu\MorphRank\Models\Forms;

use WalkerChiu\Core\Models\Forms\FormRequest;

class LevelDeleteFormRequest extends FormRequest
{
    /**
     * @Override Illuminate\Foundation\Http\FormRequest::getValidatorInstance
     */
    protected function getValidatorInstance()
    {
        return parent::getValidatorInstance();
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return Array
     */
    public function attributes()
    {
        return [
            'host_type'  => trans('php-morph-rank::level.host_type'),
            'host_id'    => trans('php-morph-rank::level.host_id'),
            'morph_type' => trans('php-morph-rank::level.morph_type'),
            'morph_id'   => trans('php-morph-rank::level.morph_id')
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return Array
     */
    public function rules()
    {
        return [
            'id'         => ['required','string','exists:'.config('wk-core.table.morph-rank.levels').',id'],
            'host_type'  => 'required_with:host_id|string',
            'host_id'    => 'required_with:host_type|string',
            'morph_type' => 'required|string',
            'morph_id'   => 'required|string'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return Array
     */
    public function messages()
    {
        return [
            'id.required'             => trans('php-core::validation.required'),
            'id.string'               => trans('php-core::validation.string'),
            'id.exists'               => trans('php-core::validation.exists'),
            'host_type.required_with' => trans('php-core::validation.required_with'),
            'host_type.string'        => trans('php-core::validation.string'),
            'host_id.required_with'   => trans('php-core::validation.required_with'),
            'host_id.string'          => trans('php-core::validation.string'),
            'morph_type.required'     => trans('php-core::validation.required'),
            'morph_type.string'       => trans('php-core::validation.string'),
            'morph_id.required'       => trans('php-core::validation.required'),
            'morph_id.string'         => trans('php-core::validation.string')
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after( function ($validator) {
            $data = $validator->getData();

            $record = config('wk-core.class.morph-rank.level')::where('id', $data['id'])
                        ->where('morph_type', $data['morph_type'])
                        ->where('morph_id', $data['morph_id'])
                        ->first();
            if (empty($record))
                $validator->errors()->add('id', trans('php-core::validation.exists'));
        });
    }
}
