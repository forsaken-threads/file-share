<?php

namespace App\Rules;

use ForsakenThreads\Diplomatic\Client;
use Illuminate\Contracts\Validation\Rule;

class Recaptcha implements Rule
{
    /**
     * Create a new rule instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $client = new Client('https://www.google.com');
        $client->post('/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $value,
            'remoteip' => request()->getClientIp(),
        ])->saveRawResponse($response);

        $json = json_decode($response, true);

        if (empty($json) || empty($json['success'])) {
            return false;
        }

        return $json['success'];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You are not a human.';
    }
}
