<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PaystackController extends Controller
{
    protected PaystackService $paystack;

    public function __construct(PaystackService $paystack)
    {
        $this->paystack = $paystack;
    }

    /**
     * Payment callback (no auth required - Paystack calls this)
     */
    public function callback(Request $request): RedirectResponse
    {
        $reference = $request->reference;
        
        Log::info('Paystack callback received', ['reference' => $reference]);

        if (!$reference) {
            Log::warning('No reference in Paystack callback');
            return redirect()->route('payment.failed');
        }

        // Verify transaction
        $response = $this->paystack->verifyTransaction($reference);
        
        Log::info('Paystack verification response', ['response' => $response]);

        if ($response && isset($response['status']) && $response['status']) {
            if (isset($response['data']['status']) && $response['data']['status'] === 'success') {
                
                // Get order ID from metadata
                $metadata = null;
                if (isset($response['data']['metadata'])) {
                    $metadata = is_string($response['data']['metadata']) 
                        ? json_decode($response['data']['metadata'], true) 
                        : $response['data']['metadata'];
                }
                
                Log::info('Payment metadata', ['metadata' => $metadata]);
                
                $orderId = $metadata['order_id'] ?? null;
                
                if ($orderId) {
                    $order = Order::find($orderId);
                    if ($order) {
                        // Mark order as paid
                        $order->update([
                            'status' => 'paid',
                            'payment_status' => 'success',
                            'payment_data' => $response['data'],
                            'payment_reference' => $reference,
                        ]);

                        Log::info('Order marked as paid', ['order_id' => $orderId]);

                        // Generate tickets
                        $this->generateTickets($order);

                        return redirect()->route('payment.success', ['order' => $order->id]);
                    }
                }
                
                Log::error('Order not found for reference', ['reference' => $reference]);
                return redirect()->route('payment.failed');
            }
        }

        Log::error('Payment verification failed', ['response' => $response]);
        return redirect()->route('payment.failed');
    }

    /**
     * Success page (no auth required - accessible after payment)
     */
    public function success(Order $order): View
    {
        return view('payment.success', compact('order'));
    }

    /**
     * Failed page
     */
    public function failed(): View
    {
        return view('payment.failed');
    }

    /**
     * Generate tickets for order
     */
    private function generateTickets(Order $order): void
    {
        $ticketDetails = $order->ticket_details;
        
        if (is_string($ticketDetails)) {
            $ticketDetails = json_decode($ticketDetails, true);
        }
        
        if (empty($ticketDetails)) {
            Log::warning('No ticket details found for order', ['order_id' => $order->id]);
            return;
        }

        $quantity = $ticketDetails['quantity'] ?? 1;
        
        for ($i = 0; $i < $quantity; $i++) {
            Ticket::create([
                'order_id' => $order->id,
                'event_id' => $order->event_id,
                'ticket_number' => $this->generateTicketNumber(),
                'tier_name' => $ticketDetails['tier'] ?? 'Regular',
                'price' => $ticketDetails['price_per_ticket'] ?? $order->total_amount / $quantity,
                'status' => 'sold',
            ]);
        }
        
        Log::info('Tickets generated', [
            'order_id' => $order->id,
            'quantity' => $quantity
        ]);
    }

    /**
     * Generate unique ticket number
     */
    private function generateTicketNumber(): string
    {
        return 'TKT-' . strtoupper(uniqid()) . '-' . rand(1000, 9999);
    }

    /**
     * Webhook for real-time updates
     */
    public function webhook(Request $request)
    {
        Log::info('Paystack webhook received', $request->all());
        
        // Verify webhook signature
        $signature = $request->header('x-paystack-signature');
        $payload = $request->getContent();

        $expected = hash_hmac('sha512', $payload, config('services.paystack.secret_key'));
        
        if (!hash_equals($expected, $signature)) {
            Log::warning('Invalid Paystack webhook signature');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $event = $request->input('event');
        $data = $request->input('data');

        if ($event === 'charge.success') {
            $reference = $data['reference'];
            
            // Find order by payment reference
            $order = Order::where('payment_reference', $reference)->first();
            
            if ($order && $order->status !== 'paid') {
                $order->update([
                    'status' => 'paid',
                    'payment_status' => 'success',
                    'payment_data' => $data,
                ]);
                
                $this->generateTickets($order);
                Log::info('Webhook: Order marked as paid', ['order_id' => $order->id]);
            }
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Initialize payment (requires auth)
     */
    public function initialize(Request $request): RedirectResponse
    {
        $order = Order::findOrFail($request->order_id);
        
        $reference = 'TICKET-' . time() . '-' . uniqid();
        
        $attendeeDetails = is_string($order->attendee_details) 
            ? json_decode($order->attendee_details, true) 
            : $order->attendee_details;
        
        $paymentData = [
            'amount' => (int) ($order->total_amount * 100),
            'email' => $attendeeDetails['email'] ?? auth()->user()->email,
            'reference' => $reference,
            'callback_url' => route('payment.callback'),
            'metadata' => json_encode([
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]),
        ];

        $response = $this->paystack->initializeTransaction($paymentData);

        if ($response && isset($response['status']) && $response['status']) {
            $order->update(['payment_reference' => $reference]);
            return redirect($response['data']['authorization_url']);
        }

        return back()->with('error', 'Unable to initialize payment');
    }
}