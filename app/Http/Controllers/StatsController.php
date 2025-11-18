<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatsController extends Controller
{
    /**
     * Ambil data customer growth dengan berbagai mode grouping
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * Query Parameters:
     * - mode: yearly|monthly|weekly|daily (default: monthly)
     * - year: tahun tertentu untuk mode yearly (default: tahun sekarang)
     * - start_date: tanggal awal untuk mode daily (format: Y-m-d)
     * - end_date: tanggal akhir untuk mode daily (format: Y-m-d)
     */
    public function customerGrowth(Request $request)
    {
        $mode = $request->get('mode', 'monthly');
        $year = $request->get('year', now()->year);
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Validasi input
        $mode = in_array($mode, ['yearly', 'monthly', 'weekly', 'daily']) ? $mode : 'monthly';

        $query = Customer::query();

        // Ambil data sesuai mode
        switch ($mode) {
            case 'yearly':
                $data = $this->getYearlyData($query);
                break;
            case 'monthly':
                $data = $this->getMonthlyData($query, $year);
                break;
            case 'weekly':
                $data = $this->getWeeklyData($query, $year);
                break;
            case 'daily':
                $data = $this->getDailyData($query, $startDate, $endDate);
                break;
            default:
                $data = $this->getMonthlyData($query, $year);
        }

        return response()->json($data);
    }

    /**
     * Data Yearly - group by tahun
     */
    private function getYearlyData($query)
    {
        $data = $query
            ->selectRaw('YEAR(created_at) as period, COUNT(*) as count')
            ->groupByRaw('YEAR(created_at)')
            ->orderBy('period', 'asc')
            ->get();

        $labels = $data->map(fn($item) => (int)$item->period)->toArray();
        $values = $data->map(fn($item) => (int)$item->count)->toArray();

        return [
            'mode' => 'yearly',
            'labels' => $labels,
            'values' => $values,
            'total' => array_sum($values),
        ];
    }

    /**
     * Data Monthly - group by bulan dalam tahun tertentu
     */
    private function getMonthlyData($query, $year)
    {
        $data = $query
            ->whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as period, COUNT(*) as count')
            ->groupByRaw('MONTH(created_at)')
            ->orderBy('period', 'asc')
            ->get();

        $monthNames = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];

        // Pastikan semua bulan ada (bahkan yang tidak ada datanya)
        $allMonths = collect(range(1, 12))->mapWithKeys(function ($month) use ($monthNames) {
            return [$month => 0];
        });

        // Merge dengan data yang ada
        foreach ($data as $item) {
            $allMonths[$item->period] = $item->count;
        }

        $labels = $allMonths->keys()->map(fn($m) => $monthNames[$m - 1])->toArray();
        $values = $allMonths->values()->toArray();

        return [
            'mode' => 'monthly',
            'year' => $year,
            'labels' => $labels,
            'values' => $values,
            'total' => array_sum($values),
        ];
    }

    /**
     * Data Weekly - group by minggu dalam tahun tertentu
     */
    private function getWeeklyData($query, $year)
    {
        $data = $query
            ->whereYear('created_at', $year)
            ->selectRaw('WEEK(created_at) as period, COUNT(*) as count')
            ->groupByRaw('WEEK(created_at)')
            ->orderBy('period', 'asc')
            ->get();

        $labels = $data->map(function ($item) {
            return 'W' . str_pad($item->period, 2, '0', STR_PAD_LEFT);
        })->toArray();

        $values = $data->map(fn($item) => (int)$item->count)->toArray();

        return [
            'mode' => 'weekly',
            'year' => $year,
            'labels' => $labels,
            'values' => $values,
            'total' => array_sum($values),
        ];
    }

    /**
     * Data Daily - group by tanggal dengan optional date range
     */
    private function getDailyData($query, $startDate = null, $endDate = null)
    {
        // Set default date range (30 hari terakhir)
        if (!$startDate || !$endDate) {
            $endDate = now()->format('Y-m-d');
            $startDate = now()->subDays(29)->format('Y-m-d');
        }

        // Validasi format tanggal
        try {
            $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
            $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
        } catch (\Exception $e) {
            return [
                'mode' => 'daily',
                'error' => 'Invalid date format. Use Y-m-d',
                'labels' => [],
                'values' => [],
            ];
        }

        // Pastikan startDate tidak lebih besar dari endDate
        if ($start > $end) {
            [$start, $end] = [$end, $start];
        }

        $data = $query
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as period, COUNT(*) as count')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('period', 'asc')
            ->get();

        // Generate semua tanggal dalam range (untuk gap filling)
        $allDates = collect();
        $current = $start->copy();
        while ($current <= $end) {
            $allDates[$current->format('Y-m-d')] = 0;
            $current->addDay();
        }

        // Merge dengan data yang ada
        foreach ($data as $item) {
            $allDates[$item->period] = (int)$item->count;
        }

        $labels = $allDates->keys()
            ->map(fn($date) => Carbon::parse($date)->format('d M'))
            ->toArray();

        $values = $allDates->values()->toArray();

        return [
            'mode' => 'daily',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'labels' => $labels,
            'values' => $values,
            'total' => array_sum($values),
        ];
    }
}
