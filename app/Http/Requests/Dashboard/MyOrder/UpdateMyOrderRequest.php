<?php

namespace App\Http\Requests\Dashboard\MyOrder;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Order;


class UpdateMyOrderRequest extends FormRequest
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
            'buyer_id' => [
                'nullable', 'integer',
            ],
            'freelancer_id' => [
                'nullable', 'integer',
            ],
            'service_id' => [
                'required', 'integer',
            ],
            'file' => [
                'required', 'mimes:zip', 'max:1024'
            ],
            'note' => [
                'required', 'string', 'max:10000',
            ],
            'expired' => [
                'nullable', 'date'
            ],
            'order_status_id' => [
                'nullable', 'integer'
            ],
        ];
    }
}
