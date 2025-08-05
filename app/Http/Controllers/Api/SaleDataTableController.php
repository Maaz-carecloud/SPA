<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductSale;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class SaleDataTableController extends Controller
{
    public function __invoke(Request $request)
    {
        $sales = ProductSale::with(['customer', 'items', 'payments'])
            ->select('product_sales.*');

        return DataTables::of($sales)
            ->addColumn('customer_name', function ($sale) {
                return $sale->customer->name ?? 'Walk-in Customer';
            })
            ->addColumn('total_amount', function ($sale) {
                return 'PKR ' . number_format($sale->grand_total, 2);
            })
            ->addColumn('paid_amount', function ($sale) {
                return 'PKR ' . number_format($sale->total_paid, 2);
            })
            ->addColumn('remaining_balance', function ($sale) {
                $remaining = $sale->remaining_balance;
                $class = $remaining <= 0 ? 'text-success' : 'text-danger';
                return '<span class="' . $class . '">PKR ' . number_format($remaining, 2) . '</span>';
            })
            ->addColumn('payment_status_badge', function ($sale) {
                return $sale->payment_status_badge;
            })
            ->addColumn('actions', function ($sale) {
                return '
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary" 
                                wire:click="viewPayments(' . $sale->id . ')" 
                                title="View Payments">
                            <i class="fas fa-credit-card"></i>
                        </button>
                        <a href="/sales/' . $sale->id . '/edit" 
                           class="btn btn-outline-warning" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="/sales/' . $sale->id . '/view" 
                           class="btn btn-outline-info" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn btn-outline-danger" 
                                wire:click="confirmDelete(' . $sale->id . ')" 
                                title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['remaining_balance', 'payment_status_badge', 'actions'])
            ->make(true);
    }
}
