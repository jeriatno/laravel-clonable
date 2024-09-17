<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class FilterBuilder
{
    protected $query;

    /**
     * Static method to create a new instance of FilterBuilder
     * @param string $modelClass
     * @return FilterBuilder
     */
    public static function for(string $modelClass): self
    {
        $model = new $modelClass;
        return new self($model);
    }

    /**
     * Constructor for FilterBuilder
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->query = $model->newQuery();
    }

    /**
     * Apply filters to the query.
     * @param array $filters
     * @return $this
     */
    public function applyFilters(array $filters): self
    {
        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                $this->applyDotNotationFilter($key, $value);
            }
        }

        return $this;
    }

    /**
     * Apply dot notation filters dynamically.
     * @param string $key
     * @param mixed $value
     */
    protected function applyDotNotationFilter(string $key, $value): void
    {
        // Regex pattern to detect date range in the format "MM/DD/YYYY - MM/DD/YYYY"
        $dateRangePattern = '/^\d{2}\/\d{2}\/\d{4} - \d{2}\/\d{2}\/\d{4}$/';

        // Check if the value matches the date range format
        if (is_string($value) && preg_match($dateRangePattern, $value)) {
            // Assume value is a date range, split it into start and end dates
            [$startDate, $endDate] = explode(' - ', $value);

            $this->query->whereBetween($key, [
                Carbon::parse($startDate),
                Carbon::parse($endDate)
            ]);
        } else {
            // Handle dot notation or direct where clause as usual
            if (str_contains($key, '.')) {
                $relations = explode('.', $key);
                $field = array_pop($relations);
                $relationPath = implode('.', $relations);

                // Use whereHas to handle relation filters dynamically
                $this->query->whereHas($relationPath, function (Builder $query) use ($field, $value) {
                    $query->where($field, $value);
                });
            } else {
                // Apply direct where filter on the column
                $this->query->where($key, $value);
            }
        }
    }


    /**
     * Apply the given conditions.
     * @param array $with
     * @return $this
     */
    public function with(array $with): self
    {
        $this->query->with($with);
        return $this;
    }

    /**
     * Apply a callback if the condition is true.
     * @param bool $condition
     * @param callable $callback
     * @return $this
     */
    public function when(bool $condition, callable $callback): self
    {
        if ($condition) {
            $callback($this->query);
        }
        return $this;
    }

    /**
     * Add a where clause to the query.
     * @param string $column
     * @param mixed $value
     * @return $this
     */
    public function where(string $column, $value): self
    {
        $this->query->where($column, $value);
        return $this;
    }

    /**
     * Order by the latest records.
     * @return $this
     */
    public function latest(): self
    {
        $this->query->latest();
        return $this;
    }

    /**
     * Get the query builder instance.
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return $this->query;
    }

    /**
     * Execute the query and get the results.
     * @return Collection|array
     */
    public function get()
    {
        return $this->query->get();
    }
}
