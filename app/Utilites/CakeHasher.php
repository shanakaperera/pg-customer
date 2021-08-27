<?php

namespace App\Utilites;

use Illuminate\Hashing\AbstractHasher;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class CakeHasher extends AbstractHasher implements HasherContract
{

    public function make($value, array $options = [])
    {
        return sha1(env('HASH_KEY') . $value);
    }

    public function check($value, $hashedValue, array $options = [])
    {
        return $this->make($value) === $hashedValue;
    }

    public function needsRehash($hashedValue, array $options = [])
    {
        return false;
    }
}
