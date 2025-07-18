<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Filament\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ActivateProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Product $product;

    /**
     * Create a new job instance.
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->product->status = 'active'; // Example of setting the product status
        $this->product->save();
        Notification::make()
                            ->title('Product Activated')
                            ->body('Product ' . $this->product->name . ' has been activated successfully.')
                            ->success()
                            ->send();
        Log::info('Activate action triggered for product: ' . $this->product->name);
        // Place any additional logic here
    }
}
