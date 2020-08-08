<?php

namespace Binota\LaravelHashidHelpers\Concerns;

use Illuminate\Contracts\Support\Arrayable;

trait QueryWithHashid
{
    public function scopeWhereHashidKey($query, $hashId)
    {
        if (is_array($hashId) || $hashId instanceof Arrayable) {
            $ids = collect($hashId)->each(function ($hashId) {
                return $this->getHashidDriver()->decode($hashId);
            });

            $query->whereIn($this->getKeyName(), $ids);

            return $query;
        }

        return $query->whereKey($this->getHashidDriver()->decode($hashId));
    }

    public function scopeFindHashid($query, $hashId, $columns = ['*'])
    {
        if (is_array($hashId) || $hashId instanceof Arrayable) {
            return $query->findManyHashid($hashId, $columns);
        }

        return $query->whereHashidKey($hashId)->first($columns);
    }

    public function scopeFindManyHashid($query, $hashIds, $columns = ['*'])
    {
        $hashIds = $hashIds instanceof Arrayable ? $hashIds->toArray() : $hashIds;

        if (empty($hashIds)) {
            return $this->newCollection();
        }

        return $query->whereKey($hashIds)->get($columns);
    }

    public function scopeFindHashidOrFail($query, $hashId, $columns = ['*'])
    {
        return $query->findOrFail($this->getHashidDriver()->decode($hashId), $columns);
    }

    public function scopeFindHashidOrNew($query, $hashId, $columns = ['*'])
    {
        return $query->findOrNew($this->getHashidDriver()->decode($hashId), $columns);
    }
}
