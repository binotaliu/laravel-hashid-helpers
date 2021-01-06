<?php

namespace Binota\LaravelHashidHelpers\Concerns;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

trait HashidRouteBinding
{
    public function getRouteKey()
    {
        return $this->getHashidDriver()->encode(
            $this->getAttribute($this->getRouteKeyName())
        );
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? $this->getRouteKeyName(), $this->getHashidDriver()->decode($value))->first();
    }

    public function resolveChildRouteBinding($childType, $value, $field)
    {
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
