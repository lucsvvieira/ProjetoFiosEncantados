<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Crypt;

trait EncryptsCardData
{
    public function setCardHolderNameAttribute($value)
    {
        $this->attributes['card_holder_name'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getCardHolderNameAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setCardNumberAttribute($value)
    {
        $this->attributes['card_number'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getCardNumberAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setCardExpiryAttribute($value)
    {
        $this->attributes['card_expiry'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getCardExpiryAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setCardCvvAttribute($value)
    {
        $this->attributes['card_cvv'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getCardCvvAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function getLastFourDigits()
    {
        if (!$this->card_number) {
            return null;
        }
        return str_repeat('*', 12) . substr($this->card_number, -4);
    }
}
