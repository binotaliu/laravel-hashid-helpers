<?php

namespace Binota\LaravelHashidHelpers\Concerns;

trait HasHashid
{
    protected $hashidConnection = '';

    public function getHashid()
    {
        if (($key = $this->getKey()) === null) {
            return null;
        }

        return $this->getHashidDriver()->encode($key);
    }

    /**
     * @return \Elfsundae\Laravel\Hashid\DriverInterface
     */
    protected function getHashidDriver()
    {
        return app('hashid')->connection($this->hashidConnection);
    }
}
