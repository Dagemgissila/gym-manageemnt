<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

trait Filterable
{
    /**
     * Apply filters to the query.
     *
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $params)
    {
        // Search
        if (!empty($params['q'])) {
            $query->where(function ($q) use ($params) {
                foreach ($this->searchable as $field) {
                    $q->orWhere($field, 'LIKE', "%{$params['q']}%");
                }
            });
        }

        // Filters
        foreach ($this->allowedFilters as $filter) {
            if (isset($params[$filter])) {
                $value = $params[$filter];

                if (method_exists($this, $method = 'scope' . ucfirst($filter))) {
                    // Explicitly pass $query as the first argument
                    $this->$method($query, $value);
                } else {
                    $query->where($filter, $value);
                }
            }
        }

        // Sorting
        $sortBy = $params['sortBy'] ?? $this->defaultSortField;
        $orderBy = $params['orderBy'] ?? $this->defaultSortOrder;

        $query->orderBy($sortBy, $orderBy);

        return $query;
    }



    public function scopePaginateResults(Builder $query, array $params)
    {
        $perPage = $params['itemsPerPage'] ?? null;

        // Return all results if itemsPerPage is -1 or not provided
        if ($perPage == -1 || $perPage === null) {
            $results = $query->get();
            return new LengthAwarePaginator(
                $results,
                $results->count(),
                $results->count() ?: 1, // Prevent division by zero
                LengthAwarePaginator::resolveCurrentPage(),
                ['path' => request()->url()]
            );
        }

        // Return paginated results
        return $query->paginate($perPage);
    }
}
