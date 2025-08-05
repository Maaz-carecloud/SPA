<?php

namespace App\Http\Controllers;

use App\Models\ProductPurchase;
use Illuminate\Http\Request;

class PurchaseDataTableController extends Controller
{
    public function getDataTableRows(Request $request)
    {
        $length = $request->input('length');
        $start = $request->input('start');

        $query = ProductPurchase::with(['supplier', 'warehouse', 'purchasedItems', 'payments']);

        // Search
        if (!empty($request['search']['value'])) {
            $searchValue = $request['search']['value'];
            $query->where(function($q) use ($searchValue) {
                $q->where('reference_no', 'like', '%' . $searchValue . '%')
                  ->orWhereHas('supplier', function($sq) use ($searchValue) {
                      $sq->where('company_name', 'like', '%' . $searchValue . '%');
                  })
                  ->orWhereHas('warehouse', function($wq) use ($searchValue) {
                      $wq->where('name', 'like', '%' . $searchValue . '%');
                  });
            });
        }

        $total = $query->count();

        if ($length == -1) {
            $length = $total;
        }

        $query->skip($start)->take($length);
        $purchases = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        foreach ($purchases as $index => $purchase) {
            $subtotal = $purchase->purchasedItems->sum(function ($item) {
                return $item->quantity * $item->unit_price;
            });
            $discountAmount = $subtotal * ($purchase->discount / 100);
            $taxAmount = ($subtotal - $discountAmount) * ($purchase->tax / 100);
            $grandTotal = $subtotal - $discountAmount + $taxAmount;
            $totalPaid = $purchase->payments->sum('paid_amount');
            $remainingBalance = $grandTotal - $totalPaid;

            $paymentStatusBadge = match($purchase->payment_status) {
                'pending' => '<span class="badge bg-warning">Pending</span>',
                'partial_paid' => '<span class="badge bg-info">Partial Paid</span>',
                'fully_paid' => '<span class="badge bg-success">Fully Paid</span>',
                default => '<span class="badge bg-secondary">Unknown</span>'
            };

            $rows[] = [
                $start + $index + 1,
                e($purchase->reference_no),
                e($purchase->supplier->company_name ?? 'N/A'),
                e($purchase->warehouse->name ?? 'N/A'),
                $purchase->purchase_date->format('d M Y'),
                'PKR ' . number_format($grandTotal, 2),
                'PKR ' . number_format($totalPaid, 2),
                'PKR ' . number_format($remainingBalance, 2),
                $paymentStatusBadge,
                '<div class="action-items">'
                . '<span><a href="#" onclick="Livewire.dispatch(\'edit-mode\', {id: ' . $purchase->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                . '<span><a href="#" onclick="viewPayments(' . $purchase->id . ')"><i class="fa fa-credit-card"></i></a></span>'
                . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $purchase->id . '"><i class="fa fa-trash"></i></a></span>'
                . '</div>',
            ];
        }

        return response()->json([
            'draw' => intval($request['draw']),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $rows,
        ]);
    }
}
