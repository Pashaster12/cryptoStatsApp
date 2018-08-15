<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use CryptoStat;

class IsValidAddress implements Rule
{
    private $currency;
    
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($currency)
    {
        $this->currency = $currency;
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
        $is_valid = CryptoStat::validateAddress($this->currency, $value);
        return $is_valid;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute you entered is not a valid ' . $this->currency . ' address.';
    }
}
