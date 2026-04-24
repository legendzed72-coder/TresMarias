<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RevenueService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    protected $revenueService;

    public function __construct(RevenueService $revenueService)
    {
        $this->revenueService = $revenueService;
    }

    /**
     * Show daily revenue report
     */
    public function daily(Request $request)
    {
        $date = $request->get('date', Carbon::now()->toDateString());
        $products = $this->revenueService->getDailyRevenue($date);
        $overview = $this->revenueService->getRevenueOverview(
            Carbon::parse($date)->startOfDay(),
            Carbon::parse($date)->endOfDay()
        );

        return view('admin.reports.revenue.daily', [
            'products' => $products,
            'overview' => $overview,
            'selectedDate' => Carbon::parse($date),
            'pageTitle' => 'Daily Revenue Report'
        ]);
    }

    /**
     * Show weekly revenue report
     */
    public function weekly(Request $request)
    {
        $date = $request->get('date', Carbon::now()->toDateString());
        $products = $this->revenueService->getWeeklyRevenue($date);
        
        $startDate = Carbon::parse($date)->startOfWeek();
        $endDate = (clone $startDate)->endOfWeek();
        
        $overview = $this->revenueService->getRevenueOverview($startDate, $endDate);

        return view('admin.reports.revenue.weekly', [
            'products' => $products,
            'overview' => $overview,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'pageTitle' => 'Weekly Revenue Report'
        ]);
    }

    /**
     * Show monthly revenue report
     */
    public function monthly(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        $products = $this->revenueService->getMonthlyRevenue($month, $year);
        
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = (clone $startDate)->endOfMonth();
        
        $overview = $this->revenueService->getRevenueOverview($startDate, $endDate);

        return view('admin.reports.revenue.monthly', [
            'products' => $products,
            'overview' => $overview,
            'month' => (int) $month,
            'year' => (int) $year,
            'monthName' => Carbon::createFromDate($year, $month, 1)->format('F'),
            'pageTitle' => 'Monthly Revenue Report'
        ]);
    }

    /**
     * Show yearly revenue report
     */
    public function yearly(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $products = $this->revenueService->getYearlyRevenue($year);
        
        $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear();
        $endDate = (clone $startDate)->endOfYear();
        
        $overview = $this->revenueService->getRevenueOverview($startDate, $endDate);

        return view('admin.reports.revenue.yearly', [
            'products' => $products,
            'overview' => $overview,
            'year' => (int) $year,
            'pageTitle' => 'Yearly Revenue Report'
        ]);
    }

    /**
     * Show combined dashboard with all reports
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'month');

        $dailyProducts = $this->revenueService->getDailyRevenue(null, 5);
        $weeklyProducts = $this->revenueService->getWeeklyRevenue(null, 5);
        $monthlyProducts = $this->revenueService->getMonthlyRevenue(null, null, 5);
        $yearlyProducts = $this->revenueService->getYearlyRevenue(null, 5);

        $overview = $this->revenueService->getRevenueOverview();
        $dailyTrend = $this->revenueService->getDailyTrend(30);
        $topProducts = $this->revenueService->getTopProducts($period, 10);

        return view('admin.reports.revenue.index', [
            'overview' => $overview,
            'dailyProducts' => $dailyProducts,
            'weeklyProducts' => $weeklyProducts,
            'monthlyProducts' => $monthlyProducts,
            'yearlyProducts' => $yearlyProducts,
            'dailyTrend' => $dailyTrend,
            'topProducts' => $topProducts,
            'period' => $period,
            'pageTitle' => 'Revenue Reports'
        ]);
    }

    /**
     * Get API data for charts
     */
    public function chartData(Request $request)
    {
        $type = $request->get('type', 'daily'); // daily, weekly, monthly, yearly
        $param = $request->get('param'); // date, date, month/year, year

        $data = match ($type) {
            'daily' => $this->revenueService->getDailyRevenue($param),
            'weekly' => $this->revenueService->getWeeklyRevenue($param),
            'monthly' => $this->revenueService->getMonthlyRevenue(...array_filter(explode('/', $param))),
            'yearly' => $this->revenueService->getYearlyRevenue($param),
            default => []
        };

        return response()->json($data);
    }
}
