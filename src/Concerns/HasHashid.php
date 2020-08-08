<?php

namespace Binota\LaravelHashidHelpers\Concerns;

trait HasHashid
{
    protected $hashidConnection = '';

    public function getHashid()
    {
        $hashid = $this->getHashidDriver();
        return $hashid->encode($this->getKey());
    }

    /**
     * @return \Elfsundae\Laravel\Hashid\DriverInterface
     */
    protected function getHashidDriver()
    {
        return app('hashid')->connection($this->hashidConnection);
    }
}
