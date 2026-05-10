<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TicketPurchaseController extends Controller
{
    protected PaystackService $paystack;

    public function __construct(PaystackService $paystack)
    {
        $this->paystack = $paystack;
    }

    /**
     * Show ticket purchase form
     */
    public function showForm(Event $event): \Illuminate\View\View|RedirectResponse
    {
        // Ensure event is published and has available tickets
        if ($event->status !== 'published' || $event->tickets_available <= 0) {
            return redirect()->route('home')->with('error', 'This event is no longer available.');
        }

        return view('tickets.purchase', compact('event'));
    }

    /**
     * Process ticket purchase and initialize Paystack payment
     */
    public function processPurchase(Request $request, Event $event): RedirectResponse
    {
        Log::info('TicketPurchaseController: Starting purchase process');
        
        $request->validate([
            'ticket_tier' => 'required|string',
            'quantity' => 'required|integer|min:1|max:10',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'id_number' => 'nullable|string|max:20',
        ]);

        // Get ticket tier details - decode JSON safely
        $ticketTiers = [];
        if (is_string($event->ticket_tiers)) {
            $ticketTiers = json_decode($event->ticket_tiers, true) ?? [];
        } elseif (is_array($event->ticket_tiers)) {
            $ticketTiers = $event->ticket_tiers;
        }
        
        $selectedTier = null;
        
        foreach ($ticketTiers as $tier) {
            if (is_array($tier) && isset($tier['name']) && $tier['name'] === $request->ticket_tier) {
                $selectedTier = $tier;
                break;
            }
        }

        if (!$selectedTier) {
            return back()->with('error', 'Invalid ticket tier selected.');
        }

        $totalAmount = (float) $selectedTier['price'] * (int) $request->quantity;

        // Check if enough tickets are available
        if ((int) $selectedTier['quantity'] < (int) $request->quantity) {
            return back()->with('error', 'Not enough tickets available for this tier.');
        }

        // Create order (user_id can be null for guest checkout)
        $order = Order::create([
            'order_number' => 'ORD-' . time() . '-' . Str::random(6),
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'payment_method' => 'paystack',
            'ticket_details' => json_encode([
                'tier' => $selectedTier['name'],
                'quantity' => (int) $request->quantity,
                'price_per_ticket' => (float) $selectedTier['price'],
                'total' => $totalAmount
            ]),
            'attendee_details' => json_encode([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'id_number' => $request->id_number,
            ]),
        ]);

        Log::info('Order created successfully', ['order_id' => $order->id]);

        // Initialize Paystack payment
        $reference = $this->generateReference();
        
        $paymentData = [
            'amount' => (int) ($totalAmount * 100),
            'email' => $request->email,
            'reference' => $reference,
            'callback_url' => route('payment.callback'),
            'metadata' => json_encode([
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'event_id' => $event->id,
                'event_title' => $event->title,
                'customer_name' => $request->name,
                'customer_phone' => $request->phone,
                'customer_email' => $request->email,
                'quantity' => (int) $request->quantity,
                'tier_name' => $selectedTier['name'],
            ]),
        ];

        Log::info('Initializing Paystack payment', ['payment_data' => $paymentData]);

        $response = $this->paystack->initializeTransaction($paymentData);

        Log::info('Paystack response', ['response' => $response]);

        if ($response && isset($response['status']) && $response['status']) {
            // Update order with payment reference
            $order->update([
                'payment_reference' => $reference,
            ]);

            // Redirect to Paystack payment page
            if (isset($response['data']['authorization_url'])) {
                Log::info('Redirecting to Paystack', ['url' => $response['data']['authorization_url']]);
                return redirect($response['data']['authorization_url']);
            }
        }

        // If payment initialization fails, delete the order
        $order->delete();
        
        Log::error('Paystack initialization failed', ['response' => $response]);
        
        return back()->with('error', 'Unable to initialize payment. Please try again.');
    }

    /**
     * Generate unique reference
     */
    private function generateReference(): string
    {
        return 'TICKET-' . time() . '-' . Str::random(8);
    }
}