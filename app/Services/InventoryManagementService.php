<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\ProductSale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryManagementService
{
    /**
     * Update product quantities after purchase
     */
    public function updateStockAfterPurchase(ProductPurchase $purchase)
    {
        try {
            DB::beginTransaction();

            foreach ($purchase->purchasedItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('quantity', $item->quantity);
                    
                    // Log inventory movement
                    $this->logInventoryMovement(
                        $product->id,
                        'purchase',
                        $item->quantity,
                        $product->quantity,
                        "Purchase #{$purchase->reference_no}"
                    );
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update stock after purchase: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update product quantities after sale
     */
    public function updateStockAfterSale(ProductSale $sale)
    {
        try {
            DB::beginTransaction();

            foreach ($sale->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    // Check if enough stock is available
                    if ($product->quantity < $item->quantity) {
                        throw new \Exception("Insufficient stock for product: {$product->name}. Available: {$product->quantity}, Required: {$item->quantity}");
                    }

                    $product->decrement('quantity', $item->quantity);
                    
                    // Log inventory movement
                    $this->logInventoryMovement(
                        $product->id,
                        'sale',
                        -$item->quantity,
                        $product->quantity,
                        "Sale #{$sale->reference_no}"
                    );
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update stock after sale: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Reverse stock changes (for cancelled/refunded transactions)
     */
    public function reverseStockChanges(ProductPurchase $purchase = null, ProductSale $sale = null)
    {
        try {
            DB::beginTransaction();

            if ($purchase) {
                foreach ($purchase->purchasedItems as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->decrement('quantity', $item->quantity);
                        
                        $this->logInventoryMovement(
                            $product->id,
                            'purchase_reversal',
                            -$item->quantity,
                            $product->quantity,
                            "Purchase #{$purchase->reference_no} - Reversed"
                        );
                    }
                }
            }

            if ($sale) {
                foreach ($sale->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->increment('quantity', $item->quantity);
                        
                        $this->logInventoryMovement(
                            $product->id,
                            'sale_reversal',
                            $item->quantity,
                            $product->quantity,
                            "Sale #{$sale->reference_no} - Reversed"
                        );
                    }
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reverse stock changes: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update payment status based on payments
     */
    public function updatePaymentStatus($model)
    {
        $grandTotal = $model->grand_total;
        $totalPaid = $model->total_paid;

        if ($totalPaid >= $grandTotal) {
            $status = $model instanceof ProductPurchase ? 'fully_paid' : 'paid';
        } elseif ($totalPaid > 0) {
            $status = $model instanceof ProductPurchase ? 'partial_paid' : 'partial';
        } else {
            $status = $model instanceof ProductPurchase ? 'pending' : 'due';
        }

        $model->update(['payment_status' => $status]);
        return $status;
    }

    /**
     * Get low stock products
     */
    public function getLowStockProducts($threshold = 10)
    {
        return Product::where('quantity', '<=', $threshold)
            ->where('is_active', true)
            ->orderBy('quantity', 'asc')
            ->get();
    }

    /**
     * Get inventory valuation
     */
    public function getInventoryValuation()
    {
        return Product::where('is_active', true)
            ->get()
            ->map(function ($product) {
                return [
                    'product' => $product,
                    'stock_value' => $product->quantity * $product->buying_price,
                    'retail_value' => $product->quantity * $product->selling_price,
                ];
            });
    }

    /**
     * Log inventory movement
     */
    private function logInventoryMovement($productId, $type, $quantity, $newStock, $reference)
    {
        // You can create an inventory_movements table for detailed tracking
        Log::info("Inventory Movement", [
            'product_id' => $productId,
            'type' => $type,
            'quantity_changed' => $quantity,
            'new_stock' => $newStock,
            'reference' => $reference,
            'timestamp' => now()
        ]);
    }
}
