<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Get all products ordered by date
        $products = Product::orderBy('created_date', 'desc')->get();

        // Category breakdown for chart
        $categoryData = Product::select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->get();

        // Price range breakdown for chart
        $priceRangeData = Product::select(
            DB::raw('CASE
                WHEN price < 50 THEN "Under $50"
                WHEN price BETWEEN 50 AND 200 THEN "$50 - $200"
                WHEN price BETWEEN 201 AND 500 THEN "$201 - $500"
                ELSE "Over $500"
            END as price_range'),
            DB::raw('COUNT(*) as count')
        )->groupBy('price_range')
            ->get();

        // Monthly sales total
        $monthlySalesByMonth = Product::selectRaw("CAST(strftime('%m', created_date) AS INTEGER) as month")
            ->selectRaw('SUM(price * quantity) as total_sales')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_sales', 'month');

        $monthlySales = collect(range(1, 12))->map(fn (int $month): object => (object) [
            'month' => $month,
            'total_sales' => (float) $monthlySalesByMonth->get($month, 0),
        ]);

        return view('reports', compact(
            'products',
            'categoryData',
            'priceRangeData',
            'monthlySales'
        ));
    }

    public function dataTable()
    {
        $products = Product::orderBy('created_date', 'desc')->get();

        return view('data-table', compact('products'));
    }
}
