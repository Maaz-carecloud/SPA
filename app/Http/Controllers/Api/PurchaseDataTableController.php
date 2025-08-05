<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductPurchase;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSupplier;
use App\Models\ProductWarehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PurchaseDataTableController extends Controller
{
    public function __invoke(Request $request)
    {
        $purchases = ProductPurchase::with(['supplier', 'warehouse', 'purchasedItems', 'payments'])
            ->select('product_purchases.*');

        return DataTables::of($purchases)
            ->addColumn('supplier_name', function ($purchase) {
                return $purchase->supplier->company_name ?? 'N/A';
            })
            ->addColumn('warehouse_name', function ($purchase) {
                return $purchase->warehouse->name ?? 'N/A';
            })
            ->addColumn('total_amount', function ($purchase) {
                return 'PKR ' . number_format($purchase->grand_total, 2);
            })
            ->addColumn('paid_amount', function ($purchase) {
                return 'PKR ' . number_format($purchase->total_paid, 2);
            })
            ->addColumn('remaining_balance', function ($purchase) {
                $remaining = $purchase->remaining_balance;
                $class = $remaining <= 0 ? 'text-success' : 'text-danger';
                return '<span class="' . $class . '">PKR ' . number_format($remaining, 2) . '</span>';
            })
            ->addColumn('payment_status_badge', function ($purchase) {
                return $purchase->payment_status_badge;
            })
            ->addColumn('actions', function ($purchase) {
                return '
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary" 
                                wire:click="viewPayments(' . $purchase->id . ')" 
                                title="View Payments">
                            <i class="fas fa-credit-card"></i>
                        </button>
                        <a href="/purchases/' . $purchase->id . '/edit" 
                           class="btn btn-outline-warning" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="/purchases/' . $purchase->id . '/view" 
                           class="btn btn-outline-info" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn btn-outline-danger" 
                                wire:click="confirmDelete(' . $purchase->id . ')" 
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
