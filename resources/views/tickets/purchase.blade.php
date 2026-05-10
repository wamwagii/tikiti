{{-- resources/views/tickets/purchase.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Buy Tickets - {{ $event->title }} | Tikiti Kenya</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#4648d4",
                        "secondary": "#fd761a",
                        "tertiary": "#8127cf",
                    },
                    fontFamily: {
                        "sans": ["Plus Jakarta Sans", "system-ui", "sans-serif"],
                    },
                },
            },
        }
    </script>
    <style>
        .primary-gradient {
            background: linear-gradient(135deg, #4648d4 0%, #8127cf 100%);
        }
        .secondary-cta {
            background-color: #fd761a;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .step-active {
            background: linear-gradient(135deg, #4648d4 0%, #8127cf 100%);
            color: white;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 bg-white/95 backdrop-blur-md shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-3xl hidden sm:block">confirmation_number</span>
                    <a href="{{ route('home') }}" class="text-2xl font-extrabold bg-gradient-to-r from-primary to-tertiary bg-clip-text text-transparent">
                        Tikiti Kenya
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <span class="text-gray-700 hidden sm:inline">Hi, {{ Auth::user()->name }}</span>
                        <a href="{{ url('/admin') }}" class="text-primary font-semibold px-4 py-2 hover:bg-primary/5 rounded-xl transition-all">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-secondary transition-colors">Logout</button>
                        </form>
                    @else
                        <a href="{{ url('/admin/login') }}" class="text-primary font-semibold px-4 py-2 hover:bg-primary/5 rounded-xl transition-all">Login</a>
                        <a href="{{ url('/admin/register') }}" class="primary-gradient text-white font-semibold px-6 py-2 rounded-xl shadow-md hover:shadow-lg transition-all active:scale-95">
                            Sell your event
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Progress Steps -->
            <div class="max-w-3xl mx-auto mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold">1</div>
                        <span class="text-xs text-gray-500 mt-2">Select Tickets</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-200"></div>
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold">2</div>
                        <span class="text-xs text-gray-500 mt-2">Your Details</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-200"></div>
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold">3</div>
                        <span class="text-xs text-gray-500 mt-2">Payment</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Event Details Sidebar -->
                <div class="lg:w-1/2">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-24">
                        @if($event->image)
    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-56 object-cover">
@else
    <div class="w-full h-56 primary-gradient flex items-center justify-center">
        <span class="text-white text-6xl">🎫</span>
    </div>
@endif
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $event->type == 'football' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ $event->type == 'football' ? '⚽ Football' : '🎤 Concert' }}
                                </span>
                                <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y g:i A') }}</span>
                            </div>
                            <h1 class="text-xl font-bold text-gray-900 mb-2">{{ $event->title }}</h1>
                            <div class="flex items-center text-gray-500 text-sm mb-3">
                                <span class="material-symbols-outlined text-base mr-1">location_on</span>
                                {{ $event->venue->name ?? 'TBD' }}, {{ $event->venue->location ?? '' }}
                            </div>
                            <p class="text-gray-600 text-sm">{{ Str::limit($event->description, 120) }}</p>
                            
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <h3 class="font-semibold text-gray-900 text-sm mb-2">Ticket Tiers</h3>
                                @php
                                    $tiers = is_string($event->ticket_tiers) ? json_decode($event->ticket_tiers, true) : $event->ticket_tiers;
                                @endphp
                                @if($tiers)
                                    @foreach($tiers as $tier)
                                    <div class="flex justify-between items-center py-1.5 text-sm">
                                        <span class="text-gray-600">{{ $tier['name'] }}</span>
                                        <span class="font-bold text-primary">KES {{ number_format($tier['price'], 2) }}</span>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Secure Payment Badges -->
                    <div class="bg-white rounded-2xl shadow-lg p-4 mt-4">
                        <div class="flex items-center justify-center gap-4 text-gray-500 text-xs">
                            <span class="flex items-center gap-1">🔒 Secure Payment</span>
                            <span>•</span>
                            <span class="flex items-center gap-1">📱 M-Pesa</span>
                            <span>•</span>
                            <span class="flex items-center gap-1">💳 Cards</span>
                        </div>
                    </div>
                </div>

                <!-- Purchase Form -->
                <div class="lg:w-1/2">
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Complete Your Purchase</h2>
                        <p class="text-gray-500 text-sm mb-6">Fill in the details below to secure your tickets</p>
                        
                        @if(!auth()->check())
                            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                <p class="text-blue-700 text-sm flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base">info</span>
                                    Already have an account? <a href="{{ url('/admin/login') }}" class="font-semibold underline hover:text-blue-800">Login here</a> for faster checkout.
                                </p>
                                <p class="text-blue-700 text-sm mt-2">New customer? <a href="{{ url('/admin/register') }}" class="font-semibold underline hover:text-blue-800">Create an account</a> to track your orders.</p>
                            </div>
                        @endif

                        <form action="{{ route('tickets.process', $event) }}" method="POST" id="purchaseForm">
                            @csrf
                            
                            <!-- Ticket Selection Card -->
                            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                                <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">confirmation_number</span>
                                    Select Your Tickets
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-gray-700 font-medium text-sm mb-1">Ticket Tier *</label>
                                        <select name="ticket_tier" id="ticket_tier" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                            <option value="">Choose a ticket tier</option>
                                            @if($tiers)
                                                @foreach($tiers as $tier)
                                                <option value="{{ $tier['name'] }}" data-price="{{ $tier['price'] }}" data-quantity="{{ $tier['quantity'] }}" {{ old('ticket_tier') == $tier['name'] ? 'selected' : '' }}>
                                                    {{ $tier['name'] }} - KES {{ number_format($tier['price'], 2) }}
                                                </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('ticket_tier')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-gray-700 font-medium text-sm mb-1">Quantity *</label>
                                        <input type="number" name="quantity" id="quantity" min="1" max="10" value="{{ old('quantity', 1) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                        <p class="text-gray-400 text-xs mt-1">Max 10 tickets per order</p>
                                        @error('quantity')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Price Summary -->
                                <div class="mt-4 pt-3 border-t border-gray-200">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">Price per ticket:</span>
                                        <span id="price_display" class="font-semibold text-gray-900">KES 0</span>
                                    </div>
                                    <div class="flex justify-between items-center text-lg font-bold mt-1">
                                        <span>Total Amount:</span>
                                        <span id="total_display" class="text-primary">KES 0</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Personal Details Card -->
                            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                                <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">person</span>
                                    Your Information
                                </h3>
                                
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-gray-700 font-medium text-sm mb-1">Full Name *</label>
                                        <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required placeholder="e.g., John Doe" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                        @error('name')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-gray-700 font-medium text-sm mb-1">Email Address *</label>
                                        <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required placeholder="you@example.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                        <p class="text-gray-400 text-xs mt-1">Ticket confirmation will be sent here</p>
                                        @error('email')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-gray-700 font-medium text-sm mb-1">Phone Number *</label>
                                        <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" required placeholder="254700000000" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                        <p class="text-gray-400 text-xs mt-1">For M-Pesa payment - Format: 254XXXXXXXX</p>
                                        @error('phone')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-gray-700 font-medium text-sm mb-1">ID/Passport Number <span class="text-gray-400">(Optional)</span></label>
                                        <input type="text" name="id_number" value="{{ old('id_number') }}" placeholder="Optional for verification" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                        @error('id_number')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="mb-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" required class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                                    <span class="text-sm text-gray-600">I agree to the <a href="#" class="text-primary hover:underline">Terms & Conditions</a> and <a href="#" class="text-primary hover:underline">Refund Policy</a></span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full secondary-cta text-white font-bold py-3 rounded-xl hover:brightness-110 transition-all flex items-center justify-center gap-2 shadow-lg">
                                <span class="material-symbols-outlined">lock</span>
                                Proceed to Payment
                            </button>
                            
                            <p class="text-center text-xs text-gray-400 mt-4">
                                Your payment is secure and encrypted. We accept M-Pesa, Visa, Mastercard, and Bank Transfer.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-400 text-sm">© {{ date('Y') }} Tikiti Kenya. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const ticketTier = document.getElementById('ticket_tier');
        const quantityInput = document.getElementById('quantity');
        const priceDisplay = document.getElementById('price_display');
        const totalDisplay = document.getElementById('total_display');

        function updateTotal() {
            const selectedOption = ticketTier.options[ticketTier.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const price = parseInt(selectedOption.dataset.price);
                const quantity = parseInt(quantityInput.value) || 0;
                const total = price * quantity;
                priceDisplay.textContent = `KES ${price.toLocaleString()}`;
                totalDisplay.textContent = `KES ${total.toLocaleString()}`;
            } else {
                priceDisplay.textContent = 'KES 0';
                totalDisplay.textContent = 'KES 0';
            }
        }

        ticketTier.addEventListener('change', updateTotal);
        quantityInput.addEventListener('input', updateTotal);
        
        // Initialize on page load
        updateTotal();

        // Quantity validation
        quantityInput.addEventListener('change', function() {
            let val = parseInt(this.value);
            if (val < 1) this.value = 1;
            if (val > 10) this.value = 10;
            updateTotal();
        });
    </script>
</body>
</html>