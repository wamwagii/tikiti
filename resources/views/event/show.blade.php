{{-- resources/views/event/show.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $event->title }} - Tikiti Kenya</title>
        
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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="md:flex">
                        <!-- Event Image -->
                        <div class="md:w-1/2">
                             @if($event->image)
    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover md:min-h-[500px]">
@else
    <div class="w-full h-96 md:h-full primary-gradient flex items-center justify-center">
        <span class="text-white text-6xl">🎫</span>
    </div>
@endif
                        </div>
                        
                        <!-- Event Details -->
                        <div class="md:w-1/2 p-6 md:p-8">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $event->type == 'football' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ $event->type == 'football' ? '⚽ Football Match' : '🎤 Concert' }}
                                </span>
                                <span class="text-sm text-gray-500">{{ $event->start_date->format('F j, Y g:i A') }}</span>
                            </div>
                            
                            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $event->title }}</h1>
                            
                            <div class="flex items-center text-gray-600 mb-4">
                                <span class="material-symbols-outlined text-xl mr-2">location_on</span>
                                {{ $event->venue->name ?? 'TBD' }}, {{ $event->venue->location ?? '' }}
                            </div>
                            
                            <p class="text-gray-600 mb-6 leading-relaxed">{{ $event->description }}</p>
                            
                            <div class="border-t border-gray-100 pt-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-2xl">confirmation_number</span>
                                    Ticket Prices
                                </h3>
                                <div class="space-y-2 mb-6">
                                    @if($event->ticket_tiers && count($event->ticket_tiers) > 0)
                                        @foreach($event->ticket_tiers as $tier)
                                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                            <span class="font-medium text-gray-700">{{ $tier['name'] }}</span>
                                            <span class="font-bold text-primary">KES {{ number_format($tier['price'], 2) }}</span>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                            <span class="font-medium text-gray-700">Standard Ticket</span>
                                            <span class="font-bold text-primary">KES {{ number_format($event->base_price, 2) }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Availability Badge -->
                                <div class="bg-gray-50 rounded-xl p-4 mb-6">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 flex items-center gap-2">
                                            <span class="material-symbols-outlined text-lg">inventory</span>
                                            Tickets Available:
                                        </span>
                                        <span class="font-bold text-gray-900">{{ number_format($event->tickets_available) }}</span>
                                    </div>
                                    @if($event->tickets_available < 100 && $event->tickets_available > 0)
                                        <div class="mt-2">
                                            <span class="text-xs text-secondary font-semibold">⚠️ Only {{ $event->tickets_available }} tickets left! Book now.</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Buy Ticket Button -->
                                <a href="{{ route('tickets.buy', $event) }}" class="secondary-cta text-white font-bold py-3 rounded-xl hover:brightness-110 transition-all text-center block w-full shadow-lg">
                                    Buy Tickets Now 🎫
                                </a>
                                
                                <!-- Secure Payment Info -->
                                <div class="mt-4 text-center">
                                    <p class="text-xs text-gray-400 flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-sm">verified</span>
                                        Secure Payment
                                        <span class="mx-1">•</span>
                                        <span class="material-symbols-outlined text-sm">smartphone</span>
                                        M-Pesa
                                        <span class="mx-1">•</span>
                                        <span class="material-symbols-outlined text-sm">credit_card</span>
                                        Card
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12 mt-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-start gap-8 mb-8 pb-8 border-b border-gray-700">
                    <div class="max-w-xs">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="material-symbols-outlined text-primary text-3xl">confirmation_number</span>
                            <h4 class="text-xl font-bold">Tikiti Kenya</h4>
                        </div>
                        <p class="text-gray-400">Kenya's most dependable platform for secure event ticketing and discovery.</p>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
                        <div>
                            <h5 class="font-bold mb-3">Explore</h5>
                            <ul class="space-y-2 text-gray-400">
                                <li><a href="{{ route('home') }}#events" class="hover:text-white transition-colors">Events</a></li>
                                <li><a href="{{ route('home') }}#concerts" class="hover:text-white transition-colors">Concerts</a></li>
                                <li><a href="{{ route('home') }}#football" class="hover:text-white transition-colors">Football</a></li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="font-bold mb-3">Support</h5>
                            <ul class="space-y-2 text-gray-400">
                                <li><a href="#" class="hover:text-white transition-colors">How it Works</a></li>
                                <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
                                <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="font-bold mb-3">Legal</h5>
                            <ul class="space-y-2 text-gray-400">
                                <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                                <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-gray-400 text-sm">© {{ date('Y') }} Tikiti Kenya. All rights reserved.</p>
                    <div class="flex gap-3">
                        <a href="#" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition-colors">📱</a>
                        <a href="#" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition-colors">📧</a>
                        <a href="#" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition-colors">🔗</a>
                    </div>
                </div>
            </div>
        </footer>

    </body>
</html>