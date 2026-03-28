<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class UpdateProductStockJob implements ShouldQueue
{
    use Queueable;

    protected $productId;
    protected $quantity;
    public function __construct($productId, $quantity)
    {
        $this->productId = $productId;
        $this->quantity = $quantity; 
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $product = \App\Models\Product::find($this->productId);
        if ($product && $product->stock >= $this->quantity) {
            $product->stock -= $this->quantity;
            $product->save();
        }else {
            // Handle out of stock scenario, e.g., log an error or notify the user
            Log::error("Product ID {$this->productId} is out of stock or does not have enough quantity.");
        }
    }
}
