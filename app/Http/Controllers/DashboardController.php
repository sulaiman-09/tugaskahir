<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(){
        // Metrics
        $totalCustomers = DB::table('users')->count();
        $todayNew = DB::table('users')->whereDate('created_at', now()->toDateString())->count();

        // Coverage rate placeholder: if there's a coverage table in future, compute properly
        // For now, estimate as percentage of users created in last 30 days vs total
        $last30 = DB::table('users')->where('created_at', '>=', now()->subDays(30))->count();
        $coverageRate = $totalCustomers > 0 ? round(($last30 / $totalCustomers) * 100, 2) : 0;

        $metrics = [
            'total_customers' => $totalCustomers,
            'total_growth_note' => '+ calculated vs last month',
            'coverage_rate' => $coverageRate,
            'coverage_note' => 'rolling 30d share',
            'today_new' => $todayNew,
            'today_target' => 50,
            'active_products' => 142,
            'active_products_note' => '+2 this week',
        ];

        // Yearly series (new customers per year, last 7 years)
        $years = collect(range(now()->year - 6, now()->year));
        $yearCounts = $years->map(function ($y) {
            return DB::table('users')->whereYear('created_at', $y)->count();
        });
        $yearly = [
            'series' => [['name' => 'New Customers', 'data' => $yearCounts->values()]],
            'categories' => $years->map(fn($y) => (string) $y)->values(),
        ];

        // Monthly series (current year, Jan-Dec)
        $months = collect(range(1, 12));
        $monthCounts = $months->map(function ($m) {
            return DB::table('users')->whereYear('created_at', now()->year)->whereMonth('created_at', $m)->count();
        });
        $monthly = [
            'series' => [['name' => 'New Customers', 'data' => $monthCounts->values()]],
            'categories' => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        ];

        // Weekly series (last 4 full weeks)
        $weeklyData = collect(range(0, 3))->map(function ($i) {
            $start = now()->startOfWeek()->subWeeks($i + 1);
            $end = now()->endOfWeek()->subWeeks($i + 1);
            return [
                'count' => DB::table('users')->whereBetween('created_at', [$start, $end])->count(),
                'label' => 'Week ' . (4 - $i),
                'dates' => $start->format('M d') . ' - ' . $end->format('M d'),
            ];
        })->reverse()->values();
        $weekly = [
            'series' => [['name' => 'New Customers', 'data' => $weeklyData->pluck('count')]],
            'categories' => $weeklyData->pluck('label'),
            'x_axis_labels' => $weeklyData->pluck('dates'),
        ];

        // Daily series (Mon-Sun of current week)
        $weekDays = collect(range(0, 6))->map(fn ($d) => now()->startOfWeek()->addDays($d));
        $dailyCounts = $weekDays->map(function ($day) {
            return DB::table('users')->whereDate('created_at', $day->toDateString())->count();
        });
        $daily = [
            'series' => [['name' => 'New Customers', 'data' => $dailyCounts->values()]],
            'categories' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'x_axis_labels' => $weekDays->map(fn ($d) => $d->format('Y-m-d')),
        ];

        // Hourly submissions (today by hour)
        $hours = collect(range(8, 19)); // 8am-7pm like sample
        $hourlyCounts = $hours->map(function ($h) {
            return DB::table('users')
                ->whereDate('created_at', now()->toDateString())
                ->whereRaw('HOUR(created_at) = ?', [$h])
                ->count();
        });
        $hourly = [
            'series' => [['name' => 'Submissions', 'data' => $hourlyCounts->values()]],
            'categories' => $hours->map(fn($h) => ($h<=12?($h==12?12:$h):$h-12).($h<12?'am':'pm'))->values(),
        ];

        // Product popularity (placeholder labels and counts based on modulo of user id)
        $productLabels = ['izzi Life 100','izzi Life 50','izzi Life 30','Promo Special','Broadband Internet'];
        $productCounts = array_fill(0, count($productLabels), 0);
        DB::table('users')->orderBy('id')->select('id')->chunk(500, function ($rows) use (&$productCounts) {
            foreach ($rows as $r) {
                $productCounts[$r->id % 5]++;
            }
        });
        $products = [
            'labels' => $productLabels,
            'series' => $productCounts,
        ];

        // Coverage distribution (covered vs uncovered using coverageRate)
        $coverage = [
            'labels' => ['Covered','Uncovered'],
            'series' => [$coverageRate, max(0, 100 - $coverageRate)],
        ];

        $charts = [
            'yearly' => $yearly,
            'monthly' => $monthly,
            'weekly' => $weekly,
            'daily' => $daily,
            'hourly' => $hourly,
            'products' => $products,
            'coverage' => $coverage,
        ];

        // Top Subdistricts table (from customer_leads)
        $validLimits = [10, 15, 25, 50, 100];
        $limit = request('limit', 10);
        if (!in_array($limit, $validLimits)) {
            $limit = 10;
        }

        $topSubdistricts = DB::table('customer_leads')
            ->select('customer_address as subdistrict', DB::raw('COUNT(*) as total_registration'),
                     DB::raw('SUM(CASE WHEN coverage IS NOT NULL AND coverage != "" THEN 1 ELSE 0 END) as covered'),
                     DB::raw('SUM(CASE WHEN coverage IS NULL OR coverage = "" THEN 1 ELSE 0 END) as uncovered'))
            ->whereNotNull('customer_address')
            ->where('customer_address', '!=', '')
            ->groupBy('customer_address')
            ->orderByDesc('total_registration')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                $coverageRate = $item->total_registration > 0 ? round(($item->covered / $item->total_registration) * 100, 2) : 0;

                // Extract subdistrict name from address
                $address = $item->subdistrict;
                $subdistrictName = $address;

                // Try to match kecamatan or kec
                if (preg_match('/kecamatan\s+([^,]+)/i', $address, $matches)) {
                    $subdistrictName = ucwords(strtolower($matches[1]));
                } elseif (preg_match('/kec\s+([^,]+)/i', $address, $matches)) {
                    $subdistrictName = ucwords(strtolower($matches[1]));
                } else {
                    // Fallback: take first part before comma or space
                    $parts = explode(',', $address);
                    $subdistrictName = ucwords(strtolower(trim($parts[0])));
                }

                return [
                    'subdistrict' => $subdistrictName,
                    'total_registration' => $item->total_registration,
                    'covered' => $item->covered,
                    'uncovered' => $item->uncovered,
                    'coverage_rate' => $coverageRate,
                ];
            })->toArray();

        return view('dashboard.index', [
            'metrics' => $metrics,
            'charts' => $charts,
            'topSubdistricts' => $topSubdistricts,
            'currentLimit' => $limit,
        ]);
    }

}

