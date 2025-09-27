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
            $start = now()->startOfWeek()->subWeeks($i+1);
            $end = now()->startOfWeek()->subWeeks($i);
            return DB::table('users')->whereBetween('created_at', [$start, $end])->count();
        })->reverse()->values();
        $weekly = [
            'series' => [['name' => 'New Customers', 'data' => $weeklyData]],
            'categories' => ['Week 1','Week 2','Week 3','Week 4'],
        ];

        // Daily series (Mon-Sun of current week)
        $weekDays = collect(range(0,6))->map(fn($d) => now()->startOfWeek()->addDays($d));
        $dailyCounts = $weekDays->map(function ($day) {
            return DB::table('users')->whereDate('created_at', $day->toDateString())->count();
        });
        $daily = [
            'series' => [['name' => 'New Customers', 'data' => $dailyCounts->values()]],
            'categories' => ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
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

        // Customers table (simple last 5 users)
        $customers = DB::table('users')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($u) {
                $statusIdx = $u->id % 3; // pseudo status
                $status = ['Active','Pending','Inactive'][$statusIdx];
                $class = ['status-active','status-pending','status-inactive'][$statusIdx];
                return [
                    'name' => $u->name,
                    'email' => $u->email,
                    'join_date' => optional($u->created_at)->format('Y-m-d'),
                    'status' => $status,
                    'status_class' => $class,
                ];
            })->toArray();

        return view('dashboard.index', [
            'metrics' => $metrics,
            'charts' => $charts,
            'customers' => $customers,
        ]);
    }

}

