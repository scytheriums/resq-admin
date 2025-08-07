<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Payments;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Throwable;

class DestroyExpiredOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:destroy-expired-order {order_id? : The ID of a specific order to test.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel orders with expired XENDIT_VA payments (booking fee or final payment pending).';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');

        // Jika ada argumen order_id, jalankan mode single/test.
        if ($orderId) {
            $this->info("Running in single-test mode for Order ID: {$orderId}");
            $order = Order::find($orderId);
            if (!$order) {
                $this->error("❌ Order with ID {$orderId} not found.");
                return Command::FAILURE;
            }
            $this->processExpiredOrder($order);
            $this->info("✅ Single order processing complete.");
            return Command::SUCCESS;
        }

        // Jika tidak ada argumen, jalankan mode batch.
        $this->info('Starting to process expired orders in batch mode...');
        
        // Query berdasarkan orders dengan payment expired
        $expiredOrders = Order::whereIn('payment_status', ['booking_fee_pending', 'final_payment_pending'])
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('payments')
                    ->whereRaw('payments.order_id = orders.id')
                    ->whereRaw("payment_method = 'XENDIT_VA'")
                    ->whereRaw("status = 'PENDING'")
                    ->where('created_at', '<', now()->subMinutes(15));
            })
            ->get();

        if ($expiredOrders->isEmpty()) {
            $this->info('No orders with expired payments found.');
            return Command::SUCCESS;
        }

        $this->info("Found " . $expiredOrders->count() . " orders with expired payments to process.");

        // Loop setiap order dan proses pembatalannya.
        foreach ($expiredOrders as $order) {
            $this->processExpiredOrder($order);
        }

        $this->info('✅ All expired orders have been processed.');
        return Command::SUCCESS;
    }

    /**
     * Processes the cancellation for a single order and all its pending payments.
     *
     * @param \App\Models\Order $order
     * @return void
     */
    private function processExpiredOrder(Order $order): void
    {
        $this->line("---");
        $this->line("Processing Order ID: {$order->id} (#{$order->order_number})");
        $this->line("  Payment Status: {$order->payment_status} | Order Status: {$order->order_status}");

        try {
            // Skip if already cancelled
            if ($order->order_status === 'cancelled_by_system') {
                $this->warn("   ⚠️  Order already cancelled by system. Skipping.");
                return;
            }

            // 1. Cari payments yang perlu di-expire
            $this->line("   🔍 Finding payments to expire...");
            $expiredPayments = Payments::where('order_id', $order->id)
                ->where('status', 'PENDING')
                ->where('payment_method', 'XENDIT_VA')
                ->get();

            if ($expiredPayments->isEmpty()) {
                $this->warn("   ⚠️  No PENDING XENDIT_VA payments found for this order.");
                return;
            }

            $this->info("   Found {$expiredPayments->count()} payment(s) to expire.");
            $originalPaymentStatus = $order->payment_status;

            // 2. Update payments menggunakan raw query untuk avoid ENUM issues
            foreach ($expiredPayments as $payment) {
                $this->line("   🔍 Updating payment {$payment->id} to EXPIRED...");
                
                $updated = DB::update(
                    "UPDATE payments SET status = 'EXPIRED' WHERE id = ?",
                    [$payment->id]
                );
                
                if ($updated) {
                    $this->info("   ✓ Payment ID {$payment->id} updated to EXPIRED");
                    
                    // Refresh model dan log activity
                    $payment->refresh();
                    
                    // Manual ActivityLog creation tanpa trait
                    try {
                        $actionType = $this->getPaymentExpiredActionType($originalPaymentStatus, $payment);
                        ActivityLog::create([
                            'actor_id' => 0,
                            'actor_type' => 'system',
                            'action_type' => $actionType,
                            'description' => "System automatically expired {$payment->transaction_type} payment (ID: {$payment->id}) due to 15 minutes timeout.",
                            'details' => [
                                'model_type' => get_class($payment),
                                'model_id' => $payment->id,
                                'payment_status_before' => 'PENDING',
                                'payment_status_after' => 'EXPIRED',
                                'order_id' => $order->id,
                                'payment_method' => $payment->payment_method,
                                'transaction_type' => $payment->transaction_type
                            ],
                            'ip_address' => '127.0.0.1',
                            'timestamp' => now(),
                        ]);
                        $this->info("   ✓ Payment logging completed with action: {$actionType}");
                    } catch (\Exception $logE) {
                        $this->warn("   ⚠️  Payment logging failed: " . $logE->getMessage());
                    }
                } else {
                    $this->error("   ❌ Failed to update payment {$payment->id}");
                }
            }

            // 3. Update order status menggunakan raw query
            $this->line("   🔍 Updating order status to cancelled_by_system...");
            $updated = DB::update(
                "UPDATE orders SET order_status = 'cancelled_by_system', cancellation_reason = 'expired transfer payment' WHERE id = ?",
                [$order->id]
            );
            
            if ($updated) {
                $this->info("   ✓ Order status updated to cancelled_by_system.");
                
                // Refresh order dan log activity
                $order->refresh();
                
                // Manual ActivityLog creation tanpa trait
                try {
                    $orderActionType = $this->getOrderCancelledActionType($originalPaymentStatus);
                    ActivityLog::create([
                        'actor_id' => 0,
                        'actor_type' => 'system',
                        'action_type' => $orderActionType,
                        'description' => "System automatically cancelled order {$order->order_number} due to expired payment timeout.",
                        'details' => [
                            'model_type' => get_class($order),
                            'model_id' => $order->id,
                            'order_status_before' => $order->getOriginal('order_status') ?? 'unknown',
                            'order_status_after' => 'cancelled_by_system',
                            'order_number' => $order->order_number,
                            'payment_status' => $originalPaymentStatus,
                            'cancellation_reason' => 'expired transfer payment'
                        ],
                        'ip_address' => '127.0.0.1',
                        'timestamp' => now(),
                    ]);
                    $this->info("   ✓ Order cancellation logged with action: {$orderActionType}");
                } catch (\Exception $logE) {
                    $this->warn("   ⚠️  Order logging failed: " . $logE->getMessage());
                }
            } else {
                $this->error("   ❌ Failed to update order {$order->id}");
            }

            $this->info("✅ Order {$order->id} processing completed successfully.");
            
        } catch (QueryException $e) {
            $this->error("❌ Database Query Error on Order ID {$order->id}.");
            $this->line("   <fg=red>Error Details:</>");
            $this->line("   - SQLSTATE: " . $e->getCode());
            $this->line("   - Message: " . $e->getMessage());
            
        } catch (Throwable $e) {
            $this->error("❌ An unexpected error occurred on Order ID {$order->id}: " . $e->getMessage());
        }
    }

    /**
     * Determine the action type for payment expiry based on payment status and payment details.
     *
     * @param string $paymentStatus
     * @param \App\Models\Payments $payment
     * @return string
     */
    private function getPaymentExpiredActionType(string $paymentStatus, Payments $payment): string
    {
        if ($paymentStatus === 'booking_fee_pending' || $payment->transaction_type === 'booking_fee') {
            return 'BOOKING_FEE_EXPIRED';
        }

        if ($paymentStatus === 'final_payment_pending' || $payment->transaction_type === 'final_payment') {
            return 'FINAL_PAYMENT_EXPIRED';
        }

        return 'PAYMENT_EXPIRED';
    }

    /**
     * Determine the action type for order cancellation based on payment context.
     *
     * @param string $originalPaymentStatus
     * @return string
     */
    private function getOrderCancelledActionType(string $originalPaymentStatus): string
    {
        if ($originalPaymentStatus === 'booking_fee_pending') {
            return 'ORDER_CANCELLED_BOOKING_FEE_EXPIRED';
        }

        if ($originalPaymentStatus === 'final_payment_pending') {
            return 'ORDER_CANCELLED_FINAL_PAYMENT_EXPIRED';
        }

        return 'ORDER_CANCELLED_PAYMENT_EXPIRED';
    }
}