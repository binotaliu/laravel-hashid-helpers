<?php

namespace Binota\LaravelHashidHelpers\Concerns;

/**
 * Define a `hashid` accesor
 */
trait GetHashid
{
    public function getHashidAttribute()
    {
        return $this->getHashid();
    }
}
