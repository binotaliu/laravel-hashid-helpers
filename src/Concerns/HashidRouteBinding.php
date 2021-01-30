<?php

namespace Binota\LaravelHashidHelpers\Concerns;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

trait HashidRouteBinding
{
    public function getRouteKey()
    {
        if (($hashId = $this->getAttribute($this->getRouteKeyName())) === null) {
            return null;
        }
        return $this->getHashidDriver()->encode($hashId);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if ($value === null) {
            return null;
        }
        return $this->where($field ?? $this->getRouteKeyName(), $this->getHashidDriver()->decode($value))->first();
    }

    public function resolveChildRouteBinding($childType, $value, $field)
    {
        if ($value === null) {
            return null;
        }

        $decodedValue = $this->getHashidDriver()->decode($value);
        $relationship = $this->{Str::plural(Str::camel($childType))}();

        if ($relationship instanceof HasManyThrough ||
            $relationship instanceof BelongsToMany) {
            return $relationship->where($relationship->getRelated()->getTable().'.'.$field, $decodedValue)->first();
        } else {
            return $relationship->where($field, $decodedValue)->first();
        }
    }
}
