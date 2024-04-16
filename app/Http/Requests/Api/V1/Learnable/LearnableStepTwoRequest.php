<?php

namespace App\Http\Requests\Api\V1\Learnable;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LearnableStepTwoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'parent_id' => ['required', Rule::exists('learnables', 'id')->where('user_id', auth('api')->id())->whereIn('type', ['public_package', 'private_package'])],
            'price' => ['required', 'numeric'],
            'package_type' => ['required', 'in:individual,repeat'],
            'start_date' => ['required', 'date'],
            'duration_in_hours' => ['required', 'integer'],


            'start_hour' => [$this->package_type == 'individual' ? 'required' : 'nullable'],

            'subscription_days' => [$this->package_type == 'repeat' ? 'required' : 'nullable', 'integer'],
            'duration_in_days' => [$this->package_type == 'repeat' ? 'required' : 'nullable', 'integer', 'gte:1'],

            'schedules' => $this->package_type == 'repeat' ? ['required', 'array'] : 'nullable',
            'schedules.*.day' => $this->package_type == 'repeat' ? ['required', Rule::in([0, 1, 2, 3, 4, 5, 6])] : 'nullable',
            'schedules.*.from' => $this->package_type == 'repeat' ? ['required'] : 'nullable',
            'schedules.*.to' => $this->package_type == 'repeat' ? ['required'] : 'nullable',
        ];
    }
}
