<?php

namespace WalkerChiu\MorphRank\Models\Forms;

use WalkerChiu\Core\Models\Forms\FormRequest;

class StatusDeleteFormRequest extends FormRequest
{
    /**
     * @Override Illuminate\Foundation\Http\FormRequest::getValidatorInstance
     */
    protected function getValidatorInstance()
    {
        return parent::getValidatorInstance();
    }

    public function attributes()
    {
        return [
            'host_type'  => trans('php-morph-rank::status.host_type'),
            'host_id'    => trans('php-morph-rank::status.host_id'),
            'morph_type' => trans('php-morph-rank::status.morph_type'),
            'morph_id'   => trans('php-morph-rank::status.morph_id')
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'         => ['required','integer','min:1','exists:'.config('wk-core.table.morph-rank.statuses').',id'],
            'host_type'  => 'required_with:host_id|string',
            'host_id'    => 'required_with:host_type|integer|min:1',
            'morph_type' => 'required|string',
            'morph_id'   => 'required|integer|min:1'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id.required'             => trans('php-core::validation.required'),
            'id.integer'              => trans('php-core::validation.integer'),
            'id.min'                  => trans('php-core::validation.min'),
            'id.exists'               => trans('php-core::validation.exists'),
            'host_type.required_with' => trans('php-core::validation.required_with'),
            'host_type.string'        => trans('php-core::validation.string'),
            'host_id.required_with'   => trans('php-core::validation.required_with'),
            'host_id.integer'         => trans('php-core::validation.integer'),
            'host_id.min'             => trans('php-core::validation.min'),
            'morph_type.required'     => trans('php-core::validation.required'),
            'morph_type.string'       => trans('php-core::validation.string'),
            'morph_id.required'       => trans('php-core::validation.required'),
            'morph_id.integer'        => trans('php-core::validation.integer'),
            'morph_id.min'            => trans('php-core::validation.min')
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $validator->getData();

            $record = config('wk-core.class.morph-rank.status')::where('id', $data['id'])
                        ->where('morph_type', $data['morph_type'])
                        ->where('morph_id', $data['morph_id'])
                        ->first();
            if (empty($record))
                $validator->errors()->add('id', trans('php-core::validation.exists'));
        });
    }
}
