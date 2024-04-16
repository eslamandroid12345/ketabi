<?php

namespace App\Http\Services\Api\V1\Payment\Helpers;



trait Payable
{
    private array $methods = [
        'card' => [
            'validation' => [
                'token' => ['nullable', 'exclude'],
            ],
            'invokable' => 'card',
        ],
        'apple_pay' => [
            'validation' => [
                'token' => ['required', 'exclude'],
            ],
            'invokable' => 'applePay',
        ],
        'bank_transfer' => [
            'validation' => [
                'transfer_image' => ['required', 'exclude', 'file', 'mimes:jpg,jpeg,png', 'max:512'],
                'amount' => ['required', 'numeric'],
                'bank_account_name' => ['required'],
                'bank_account_number' => ['required'],
                'bank_account_iban' => ['nullable'],
                'from_bank' => ['required'],
                'to_bank' => ['required'],
                'transfer_date' => ['required'],
                'transfer_time' => ['required'],
            ],
            'invokable' => 'bankTransfer'
        ],
    ];
}
