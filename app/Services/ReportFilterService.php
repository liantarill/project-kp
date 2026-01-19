<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ReportFilterService
{
    /**
     * Apply date range filter based on period
     * Support both Builder and JoinClause
     */
    public static function applyDateFilter($query, array $filters, string $dateColumn = 'created_at')
    {
        $period = $filters['period'] ?? 'all';

        switch ($period) {
            case 'month':
                if (!empty($filters['month']) && !empty($filters['year'])) {
                    $query->whereMonth($dateColumn, $filters['month'])
                        ->whereYear($dateColumn, $filters['year']);
                }
                break;

            case 'year':
                if (!empty($filters['year'])) {
                    $query->whereYear($dateColumn, $filters['year']);
                }
                break;

            case 'custom':
                if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                    $query->whereBetween($dateColumn, [
                        $filters['start_date'] . ' 00:00:00',
                        $filters['end_date'] . ' 23:59:59'
                    ]);
                }
                break;
        }

        return $query;
    }

    /**
     * Apply date filter conditions (for use in join clauses)
     * Returns closure to be used in join conditions
     */
    public static function getDateFilterConditions(array $filters, string $dateColumn = 'created_at'): ?\Closure
    {
        $period = $filters['period'] ?? 'all';

        if ($period === 'all') {
            return null;
        }

        return function ($join) use ($filters, $dateColumn, $period) {
            switch ($period) {
                case 'month':
                    if (!empty($filters['month']) && !empty($filters['year'])) {
                        $join->whereMonth($dateColumn, $filters['month'])
                            ->whereYear($dateColumn, $filters['year']);
                    }
                    break;

                case 'year':
                    if (!empty($filters['year'])) {
                        $join->whereYear($dateColumn, $filters['year']);
                    }
                    break;

                case 'custom':
                    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                        $join->whereBetween($dateColumn, [
                            $filters['start_date'] . ' 00:00:00',
                            $filters['end_date'] . ' 23:59:59'
                        ]);
                    }
                    break;
            }
        };
    }

    /**
     * Apply status filter
     */
    public static function applyStatusFilter(Builder $query, array $filters, string $column = 'status'): Builder
    {
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where($column, $filters['status']);
        }

        return $query;
    }

    /**
     * Apply institution type filter
     */
    public static function applyInstitutionTypeFilter(Builder $query, array $filters): Builder
    {
        if (!empty($filters['institution_type']) && $filters['institution_type'] !== 'all') {
            $query->whereHas('institution', function ($q) use ($filters) {
                $q->where('type', $filters['institution_type']);
            });
        }

        return $query;
    }

    /**
     * Apply education level filter
     */
    public static function applyLevelFilter(Builder $query, array $filters, string $column = 'level'): Builder
    {
        if (!empty($filters['level']) && $filters['level'] !== 'all') {
            $query->where($column, $filters['level']);
        }

        return $query;
    }

    /**
     * Apply all participant filters at once
     */
    public static function applyParticipantFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->tap(fn($q) => self::applyDateFilter($q, $filters, 'start_date'))
            ->tap(fn($q) => self::applyStatusFilter($q, $filters))
            ->tap(fn($q) => self::applyInstitutionTypeFilter($q, $filters))
            ->tap(fn($q) => self::applyLevelFilter($q, $filters));
    }

    /**
     * Get default filter values
     */
    public static function getDefaults(): array
    {
        return [
            'period' => 'all',
            'year' => now()->year,
            'month' => now()->month,
            'start_date' => now()->startOfMonth()->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
            'status' => 'all',
            'institution_type' => 'all',
            'level' => 'all',
        ];
    }

    /**
     * Validate and sanitize filters
     */
    public static function sanitize(array $filters): array
    {
        $defaults = self::getDefaults();

        return [
            'period' => in_array($filters['period'] ?? '', ['all', 'month', 'year', 'custom'])
                ? $filters['period']
                : $defaults['period'],
            'year' => filter_var($filters['year'] ?? $defaults['year'], FILTER_VALIDATE_INT),
            'month' => filter_var($filters['month'] ?? $defaults['month'], FILTER_VALIDATE_INT),
            'start_date' => $filters['start_date'] ?? $defaults['start_date'],
            'end_date' => $filters['end_date'] ?? $defaults['end_date'],
            'status' => $filters['status'] ?? $defaults['status'],
            'institution_type' => $filters['institution_type'] ?? $defaults['institution_type'],
            'level' => $filters['level'] ?? $defaults['level'],
        ];
    }
}
