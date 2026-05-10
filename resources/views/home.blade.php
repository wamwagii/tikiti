{{-- resources/views/home.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Tikiti - Buy Tickets for Sportpesa Premier League & Concerts in Kenya</title>
        <meta name="description" content="Buy tickets online for Sportpesa Premier League football matches and international concerts in Kenya. Secure payment with M-Pesa and cards.">

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
            .event-card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
            .event-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 12px 32px rgba(70, 72, 212, 0.12);
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
                        <a href="/" class="text-2xl font-extrabold bg-gradient-to-r from-primary to-tertiary bg-clip-text text-transparent">
                            Tikiti
                        </a>
                    </div>
                    <nav class="hidden md:flex items-center gap-8">
                        <a href="#events" class="text-primary font-semibold border-b-2 border-secondary pb-1">Events</a>
                        <a href="#holidays" class="text-gray-600 hover:text-secondary transition-colors">Holidays</a>
                        <a href="#how-it-works" class="text-gray-600 hover:text-secondary transition-colors">How it Works</a>
                        <a href="#contact" class="text-gray-600 hover:text-secondary transition-colors">Contact</a>
                    </nav>
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

            <!-- Hero Section -->
            <section class="relative min-h-[550px] overflow-hidden mb-16">
                <div class="absolute inset-0">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/40 z-10"></div>
                    <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?w=1600&h=800&fit=crop" alt="Concert crowd in Kenya">
                </div>
                <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                    <div class="max-w-2xl">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-4 leading-tight">
                            Experience Kenya's Biggest Moments
                        </h1>
                        <p class="text-lg md:text-xl text-white/90 mb-8">
                            From sold-out concerts in Nairobi to championship matches at Kasarani. Your gateway to the most exciting events across the nation.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="#events" class="secondary-cta text-white font-semibold px-8 py-3 rounded-xl shadow-lg hover:brightness-110 transition-all">Browse Events</a>
                            <a href="{{ url('/admin/register') }}" class="bg-white/10 backdrop-blur-md border border-white/20 text-white font-semibold px-8 py-3 rounded-xl hover:bg-white/20 transition-all">Sell Your Event</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Featured Events Section -->
            @if($featuredEvents->count() > 0)
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="flex justify-between items-end mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">Featured Highlights</h2>
                        <p class="text-gray-500 mt-1">Hand-picked premium experiences you can't miss.</p>
                    </div>
                    <a href="#" class="text-primary font-semibold flex items-center gap-1 hover:gap-2 transition-all">View all <span class="material-symbols-outlined text-sm">arrow_forward</span></a>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Main Featured Card (first featured event) -->
                    @php $mainFeatured = $featuredEvents->shift(); @endphp
                    <div class="lg:col-span-7 relative rounded-2xl overflow-hidden group shadow-lg h-96">
                        @if($mainFeatured->image)
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('storage/' . $mainFeatured->image) }}" alt="{{ $mainFeatured->title }}">
                        @else
                            <div class="w-full h-full primary-gradient flex items-center justify-center">
                                <span class="text-white text-6xl">🎫</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent p-6 flex flex-col justify-end">
                            <span class="bg-secondary text-white text-xs font-semibold px-3 py-1 rounded-full w-fit mb-3 uppercase tracking-wider">Featured</span>
                            <h3 class="text-2xl font-bold text-white mb-1">{{ $mainFeatured->title }}</h3>
                            <p class="text-white/80 mb-4">{{ $mainFeatured->venue->name ?? 'TBD' }} • {{ $mainFeatured->start_date->format('M d') }}</p>
                            <a href="{{ url('/events/' . $mainFeatured->id) }}" class="secondary-cta text-white font-semibold px-6 py-2 rounded-lg w-fit shadow-lg">Get Tickets</a>
                        </div>
                    </div>
                    <!-- Side Featured Cards -->
                    <div class="lg:col-span-5 flex flex-col gap-6">
                        @foreach($featuredEvents->take(2) as $event)
                        <div class="relative h-64 rounded-2xl overflow-hidden group shadow-lg">
                            @if($event->image)
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}">
                            @else
                                <div class="w-full h-full primary-gradient flex items-center justify-center">
                                    <span class="text-white text-4xl">🎫</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent p-6 flex flex-col justify-end">
                                <h3 class="text-xl font-bold text-white">{{ $event->title }}</h3>
                                <p class="text-white/80">{{ $event->venue->name ?? 'TBD' }} • {{ $event->start_date->format('M d') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            <!-- Categories Section -->
            <section class="bg-white py-12 my-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gray-50 p-6 rounded-2xl flex items-center gap-4 shadow-md hover:shadow-xl transition-all cursor-pointer group">
                            <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                                <span class="material-symbols-outlined text-3xl">sports_soccer</span>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">Football</h4>
                                <p class="text-gray-500 text-sm">{{ $footballMatches->count() }} Active Events</p>
                            </div>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-2xl flex items-center gap-4 shadow-md hover:shadow-xl transition-all cursor-pointer group">
                            <div class="w-14 h-14 rounded-xl bg-tertiary/10 flex items-center justify-center text-tertiary group-hover:bg-tertiary group-hover:text-white transition-all">
                                <span class="material-symbols-outlined text-3xl">music_note</span>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">Concerts</h4>
                                <p class="text-gray-500 text-sm">{{ $concerts->count() }} Active Events</p>
                            </div>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-2xl flex items-center gap-4 shadow-md hover:shadow-xl transition-all cursor-pointer group">
                            <div class="w-14 h-14 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-white transition-all">
                                <span class="material-symbols-outlined text-3xl">beach_access</span>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">Holidays</h4>
                                <p class="text-gray-500 text-sm">Special Events</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Upcoming Events Grid -->
            <div id="events" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Upcoming Events</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($upcomingEvents as $event)
                    <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all group">
                        <div class="h-48 overflow-hidden">
                            @if($event->image)
                                <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}">
                            @else
                                <div class="w-full h-full primary-gradient flex items-center justify-center">
                                    <span class="text-white text-4xl">🎫</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <span class="bg-primary/10 text-primary text-xs font-semibold px-2 py-1 rounded-full">{{ $event->type == 'football' ? '⚽ Football' : '🎤 Concert' }}</span>
                                <span class="text-secondary font-bold">KES {{ number_format($event->base_price, 2) }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1 truncate">{{ $event->title }}</h3>
                            <p class="text-gray-500 text-sm mb-3 flex items-center gap-1"><span class="material-symbols-outlined text-sm">calendar_today</span> {{ $event->start_date->format('M d • g:i A') }}</p>
                            <a href="{{ url('/events/' . $event->id) }}" class="w-full block text-center border-2 border-primary text-primary font-semibold py-2 rounded-lg hover:bg-primary hover:text-white transition-all">Buy Ticket</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Football Matches Section -->
            @if($footballMatches->count() > 0)
            <div id="football" class="bg-gray-100 py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900">⚽ Football Matches</h2>
                        <a href="#" class="text-primary font-semibold flex items-center gap-1 hover:gap-2 transition-all">View all <span class="material-symbols-outlined text-sm">arrow_forward</span></a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($footballMatches as $match)
                        <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all group">
                            <div class="p-4 text-center">
                                <p class="text-sm text-gray-500 mb-2">{{ $match->start_date->format('M d, Y g:i A') }}</p>
                                <h3 class="font-bold text-gray-900 mb-1">{{ $match->title }}</h3>
                                <p class="text-sm text-gray-500 mb-3">{{ $match->venue->name ?? 'TBD' }}</p>
                                <p class="text-xl font-bold text-primary mb-3">KES {{ number_format($match->base_price, 2) }}</p>
                                <a href="{{ url('/events/' . $match->id) }}" class="inline-block bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition-all">Buy Tickets</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Concerts Section -->
            @if($concerts->count() > 0)
            <div id="concerts" class="py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900">🎤 Upcoming Concerts</h2>
                        <a href="#" class="text-primary font-semibold flex items-center gap-1 hover:gap-2 transition-all">View all <span class="material-symbols-outlined text-sm">arrow_forward</span></a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($concerts as $concert)
                        <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all group">
                            <div class="p-4 text-center">
                                <p class="text-sm text-gray-500 mb-2">{{ $concert->start_date->format('M d, Y g:i A') }}</p>
                                <h3 class="font-bold text-gray-900 mb-1">{{ $concert->title }}</h3>
                                <p class="text-sm text-gray-500 mb-3">{{ $concert->venue->name ?? 'TBD' }}</p>
                                <p class="text-xl font-bold text-primary mb-3">KES {{ number_format($concert->base_price, 2) }}</p>
                                <a href="{{ url('/events/' . $concert->id) }}" class="inline-block bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition-all">Buy Tickets</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Popular Venues Section -->
            @if($popularVenues->count() > 0)
            <div id="venues" class="bg-gray-100 py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Popular Venues</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($popularVenues as $venue)
                        <div class="bg-white rounded-xl p-6 text-center shadow-md hover:shadow-xl transition-all">
                            <div class="text-5xl mb-3">🏟️</div>
                            <h3 class="font-bold text-gray-900 mb-1">{{ $venue->name }}</h3>
                            <p class="text-sm text-gray-500 mb-2">{{ $venue->location }}, {{ $venue->city }}</p>
                            <p class="text-sm text-primary font-semibold">{{ $venue->events_count }} events</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- How It Works Section -->
            <section id="how-it-works" class="primary-gradient py-16 my-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-3xl font-bold text-white mb-12">Experience the Best of Kenya in 3 Steps</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center text-white mb-4 relative">
                                <span class="material-symbols-outlined text-4xl">search</span>
                                <div class="absolute -top-2 -right-2 w-7 h-7 rounded-full bg-secondary flex items-center justify-center text-white text-sm font-bold">1</div>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Discover Events</h3>
                            <p class="text-white/80 text-center">Browse through hundreds of curated events</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center text-white mb-4 relative">
                                <span class="material-symbols-outlined text-4xl">payments</span>
                                <div class="absolute -top-2 -right-2 w-7 h-7 rounded-full bg-secondary flex items-center justify-center text-white text-sm font-bold">2</div>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Secure Payment</h3>
                            <p class="text-white/80 text-center">Pay via M-PESA, Card, or Bank transfer</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center text-white mb-4 relative">
                                <span class="material-symbols-outlined text-4xl">qr_code</span>
                                <div class="absolute -top-2 -right-2 w-7 h-7 rounded-full bg-secondary flex items-center justify-center text-white text-sm font-bold">3</div>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Instant Ticket</h3>
                            <p class="text-white/80 text-center">Receive digital ticket immediately</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials Section -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">What People Are Saying</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-xl">JW</div>
                            <div>
                                <h4 class="font-bold text-gray-900">Jane W.</h4>
                                <p class="text-gray-500 text-sm">Music Enthusiast</p>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">"The M-PESA integration is seamless! I got my tickets in less than a minute."</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full bg-tertiary/20 flex items-center justify-center text-tertiary font-bold text-xl">DK</div>
                            <div>
                                <h4 class="font-bold text-gray-900">David K.</h4>
                                <p class="text-gray-500 text-sm">Event Organizer</p>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">"Selling my conference tickets was incredibly easy. Great analytics!"</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full bg-secondary/20 flex items-center justify-center text-secondary font-bold text-xl">SO</div>
                            <div>
                                <h4 class="font-bold text-gray-900">Samuel O.</h4>
                                <p class="text-gray-500 text-sm">Football Fan</p>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">"No more queuing for derby tickets! Tikiti has changed the game."</p>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mb-8">
                <div class="primary-gradient rounded-2xl p-12 text-center">
                    <h2 class="text-3xl font-bold text-white mb-4">Ready to start your next adventure?</h2>
                    <p class="text-white/90 text-lg mb-6 max-w-2xl mx-auto">Join thousands of Kenyans discovering and booking the best experiences every month.</p>
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="{{ url('/admin/register') }}" class="bg-white text-primary font-bold px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all">Create Account</a>
                        <button class="border border-white/30 text-white font-bold px-8 py-3 rounded-xl hover:bg-white/10 transition-all">Download App</button>
                    </div>
                </div>
            </section>

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
                                <li><a href="#events" class="hover:text-white transition-colors">Events</a></li>
                                <li><a href="#concerts" class="hover:text-white transition-colors">Concerts</a></li>
                                <li><a href="#football" class="hover:text-white transition-colors">Football</a></li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="font-bold mb-3">Support</h5>
                            <ul class="space-y-2 text-gray-400">
                                <li><a href="#how-it-works" class="hover:text-white transition-colors">How it Works</a></li>
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