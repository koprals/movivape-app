<?php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Content;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $title = 'Dashboard';

    public function index(Content $content)
    {
        // Total penjualan pada bulan berjalan
        $currentMonth = Carbon::now()->month;
        $totalSales = Order::whereMonth('order_date', $currentMonth)
            ->sum('total_price');

        // Volume penjualan dalam bulan berjalan
        $totalVolume = OrderDetail::whereHas('order', function($query) use ($currentMonth) {
            $query->whereMonth('order_date', $currentMonth);
        })->sum('product_qty');

        // Top 5 produk yang sering dibeli pada bulan berjalan
        $topProducts = OrderDetail::select('product_id', DB::raw('count(*) as total'))
            ->whereHas('order', function($query) use ($currentMonth) {
                $query->whereMonth('order_date', $currentMonth);
            })
            ->groupBy('product_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->with('product')
            ->get();

        // Grafik pertumbuhan penjualan selama tahun berjalan
        $currentYear = Carbon::now()->year;
        $salesGrowth = Order::select(
            DB::raw('MONTH(order_date) as month'),
            DB::raw('SUM(total_price) as total_sales')
        )
        ->whereYear('order_date', $currentYear)
        ->groupBy(DB::raw('MONTH(order_date)'))
        ->orderBy('month')
        ->get();

        return $content
            ->title($this->title)
            ->description('Overview of the current month and year sales data')
            ->view('admin.dashboard.index', compact('totalSales', 'totalVolume', 'topProducts', 'salesGrowth'));
    }
}
