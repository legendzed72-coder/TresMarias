<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#8b4513">
	<meta name="description" content="Tres Marias — fresh bread, pastries, and neighborhood delivery. Order your favorites online for pickup or doorstep delivery.">

	<title>Tres Marias | Fresh Bakery, Delivered to Your Door</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

	@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
		@vite(['resources/css/app.css', 'resources/js/app.js'])
	@endif
</head>
<body>

	<!-- ═══════ HEADER ═══════ -->
	<header class="hdr">
		<div class="w">
			<a href="{{ route('home') }}" class="logo" aria-label="Home">
				<img src="{{ asset('images/logo.png') }}" alt="Tres Marias" class="logo-icon" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
				<span>Tres Marias</span>
			</a>

			<nav class="nav" aria-label="Primary">
				<a href="{{ route('home') }}">Home</a>
				<a href="{{ route('about') }}">About</a>
				<a href="{{ route('products.catalog') }}">Product</a>
				<a href="{{ route('contact') }}">Contact</a>
			</nav>

			<div class="hdr-cta">
				@auth
					@php $notificationCount = auth()->user()->unreadNotifications()->count(); @endphp
					<a href="{{ route('notifications.index') }}" class="inline-flex items-center justify-center p-2 rounded-xl text-bark-500 hover:text-bark-700 hover:bg-cream-100/70 transition me-2" aria-label="Notifications">
						<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
						</svg>
						@if($notificationCount)
							<span class="relative -top-1 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-red-500 px-1.5 text-[11px] font-semibold leading-none text-white">{{ $notificationCount > 9 ? '9+' : $notificationCount }}</span>
						@endif
					</a>
				@endauth
				<div class="profile-wrapper" id="profileDropdown">
					<button class="profile-toggle" onclick="this.parentElement.classList.toggle('open')" aria-label="Menu">
						<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
						</svg>
					</button>
					<div class="profile-menu">
						@auth
							<a href="{{ route('my-orders') }}">
								<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
								Order
							</a>
							<a href="{{ route('profile.edit') }}">
								<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
								Setting
							</a>
							<a href="{{ route('products.catalog') }}">
								<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
								Product
							</a>
							<a href="{{ route('profile.edit') }}">
								<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
								Profile
							</a>
							<div class="profile-divider"></div>
							<form method="POST" action="{{ route('logout') }}">
								@csrf
								<button type="submit">
									<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
									Log out
								</button>
							</form>
						@else
							@if (Route::has('login'))
								<a href="{{ route('login') }}">
									<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
									Log in
								</a>
							@endif
							@if (Route::has('register'))
								<a href="{{ route('register') }}">
									<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
									Sign up
								</a>
							@endif
						@endauth
					</div>
				</div>
			</div>
		</div>
	</header>

	<main>

		<!-- ═══════ HERO ═══════ -->
		<section class="hp-hero">
			<div class="w hero-inner">
				<div class="hero-text">
					<div class="badge">Fresh daily &bull; Delivery &amp; pickup</div>
					<h1>Welcome to Tres Marias, <em>Order Now.</em></h1>
					<p class="hero-sub">
						Browse freshly baked breads and pastries, schedule pre-orders, and get warm deliveries straight to your doorstep — all from Tres Marias.
					</p>

					<div class="hero-btns">
						@auth
							<a href="{{ route('products') }}" class="btn btn-fill btn-lg">Order now</a>
						@else
							@if (Route::has('register'))
								<a href="{{ route('register') }}" class="btn btn-fill btn-lg">Order now</a>
							@endif
							@if (Route::has('login'))
								<a href="{{ route('login') }}" class="btn btn-outline btn-lg">Sign in</a>
							@endif
						@endauth
					</div>

					<div class="hero-stats">
						<div>
							<strong>50+</strong>
							<span>Products available</span>
						</div>
						<div>
							<strong>₱5 – ₱60</strong>
							<span>Affordable treats</span>
						</div>
						<div>
							<strong>Fresh</strong>
							<span>Baked every morning</span>
						</div>
					</div>
				</div>

				@php
					$featuredProducts = App\Models\Product::active()->with('category')->take(12)->get();
				@endphp

				<div class="mosaic">
					@foreach($featuredProducts->take(3) as $product)
						@php
							$imageSrc = null;
							if ($product->image_url) {
								if (Str::startsWith($product->image_url, ['http://', 'https://'])) {
									$imageSrc = $product->image_url;
								} elseif (Str::startsWith($product->image_url, '/')) {
									$imageSrc = asset(ltrim($product->image_url, '/'));
								} else {
									$imageSrc = asset('storage/' . ltrim($product->image_url, '/'));
								}
							}
						@endphp

						@if($imageSrc)
							<img class="big" src="{{ $imageSrc }}" alt="{{ $product->name }}" loading="eager">
						@else
							<img class="big" src="{{ asset('images/08c28709-c91b-41dd-a68a-064a9bee01a7.jpg') }}" alt="Featured product" loading="eager">
						@endif
					@endforeach
				</div>
			</div>
		</section>

		<!-- ═══════ PRODUCT STRIP ═══════ -->
		<section id="products" class="strip">
			<div class="strip-title">
				<h2>Our best sellers</h2>
				<p>Freshly baked favorites — swipe to explore</p>
			</div>

			<div class="scroll-track">
				<article class="p-card">
					<img src="{{ asset('images/08c28709-c91b-41dd-a68a-064a9bee01a7.jpg') }}" alt="Cheesy Ensaymada" loading="lazy">
					<div class="p-card-body">
						<h3>Cheesy Ensaymada</h3>
						<div class="p-card-row">
							<span class="p-price">₱10.00</span>
							<span class="p-badge">Best Seller</span>
						</div>
					</div>
				</article>
				<article class="p-card">
					<img src="{{ asset('images/e599551d-cee3-4b6d-874b-6b77c924a7bf.jpg') }}" alt="Creamcheese Ensaymada" loading="lazy">
					<div class="p-card-body">
						<h3>Creamcheese Ensaymada</h3>
						<div class="p-card-row">
							<span class="p-price">₱35.00</span>
							<span class="p-badge">Best Seller</span>
						</div>
					</div>
				</article>
				<article class="p-card">
					<img src="{{ asset('images/5aecfd0c-e9bd-4b92-bd26-a58e743314bf.jpg') }}" alt="Pizza Solo" loading="lazy">
					<div class="p-card-body">
						<h3>Pizza Solo</h3>
						<div class="p-card-row">
							<span class="p-price">₱25.00</span>
							<span class="p-badge">Best Seller</span>
						</div>
					</div>
				</article>
				<article class="p-card">
					<img src="{{ asset('images/ce2dd6dd-5fcb-44ee-8787-edfb9f40a858.jpg') }}" alt="Macaroons" loading="lazy">
					<div class="p-card-body">
						<h3>Macaroons</h3>
						<div class="p-card-row">
							<span class="p-price">₱5.00</span>
							<span class="p-badge">Best Seller</span>
						</div>
					</div>
				</article>
				<article class="p-card">
					<img src="{{ asset('images/d90a1665-d957-4340-aea9-e0df1629650c.jpg') }}" alt="Korean Garlic Bread" loading="lazy">
					<div class="p-card-body">
						<h3>Korean Garlic Bread</h3>
						<div class="p-card-row">
							<span class="p-price">₱60.00</span>
						</div>
					</div>
				</article>
				<article class="p-card">
					<img src="{{ asset('images/cb5d991b-b49c-4b77-b3ae-79066be1f356.jpg') }}" alt="Spanish Bread" loading="lazy">
					<div class="p-card-body">
						<h3>Spanish Bread</h3>
						<div class="p-card-row">
							<span class="p-price">₱5.00</span>
						</div>
					</div>
				</article>
				<article class="p-card">
					<img src="{{ asset('images/4b8c8066-d1ef-43c3-8d20-09495d9acd0d.jpg') }}" alt="Ube Cream" loading="lazy">
					<div class="p-card-body">
						<h3>Ube Cream</h3>
						<div class="p-card-row">
							<span class="p-price">₱25.00</span>
						</div>
					</div>
				</article>
				<article class="p-card">
					<img src="{{ asset('images/f0782adb-de1f-4cfe-bcdb-5d1785c26c73.jpg') }}" alt="Ham & Egg" loading="lazy">
					<div class="p-card-body">
						<h3>Ham &amp; Egg</h3>
						<div class="p-card-row">
							<span class="p-price">₱25.00</span>
						</div>
					</div>
				</article>
			</div>
		</section>

		<!-- ═══════ FEATURES ═══════ -->
		<section id="features" class="feat">
			<div class="w">
				<div class="feat-head">
					<div class="badge">Platform features</div>
					<h2>Everything your neighborhood bakery needs</h2>
					<p>From the counter to the customer's phone — one system for walk-ins, pre-orders, inventory, and delivery.</p>
				</div>

				<div class="feat-grid">
					<article class="feat-card">
						<div class="feat-icon fi-pos">🧾</div>
						<h3>Walk-in &amp; online orders</h3>
						<p>Ring up counter sales and accept scheduled pre-orders in a single system with shared inventory.</p>
					</article>
					<article class="feat-card">
						<div class="feat-icon fi-inv">📦</div>
						<h3>Ingredient-level inventory</h3>
						<p>Every sale deducts stock automatically. Low-stock alerts and audit logs keep the kitchen ahead.</p>
					</article>
					<article class="feat-card">
						<div class="feat-icon fi-del">🛵</div>
						<h3>Delivery dispatch</h3>
						<p>Assign riders, track deliveries, and give customers real-time updates from pickup to doorstep.</p>
					</article>
					<article class="feat-card">
						<div class="feat-icon fi-off">📴</div>
						<h3>Offline-ready POS</h3>
						<p>Cashiers keep selling when the internet drops. Transactions sync automatically when reconnected.</p>
					</article>
					<article class="feat-card">
						<div class="feat-icon fi-pay">💳</div>
						<h3>Flexible payments</h3>
						<p>Cash, card, GCash, and Maya — every transaction logged with method and receipt-ready totals.</p>
					</article>
					<article class="feat-card">
						<div class="feat-icon fi-rpt">📊</div>
						<h3>Sales dashboards</h3>
						<p>Revenue, top products, hourly patterns, and inventory health — all in one real-time view.</p>
					</article>
				</div>
			</div>
		</section>

		<!-- ═══════ HOW IT WORKS ═══════ -->
		<section id="how" class="how">
			<div class="w">
				<div class="how-head">
					<div class="badge">How it works</div>
					<h2>Three simple steps to fresh bread</h2>
					<p>Browse, order, and receive — with minimal friction from screen to doorstep.</p>
				</div>

				<div class="steps">
					<div class="step">
						<div class="step-num">1</div>
						<h3>Browse what's fresh</h3>
						<p>Open the app and see what's baked, available, and ready for pickup or delivery right now.</p>
					</div>
					<div class="step">
						<div class="step-num">2</div>
						<h3>Place your order</h3>
						<p>Choose items, pick a delivery or pickup slot, and confirm. Pre-order tomorrow's batch with confidence.</p>
					</div>
					<div class="step">
						<div class="step-num">3</div>
						<h3>Get warm deliveries</h3>
						<p>Track your order from the oven to dispatch. A neighborhood rider delivers it fresh to your door.</p>
					</div>
				</div>
			</div>
		</section>

		<!-- ═══════ PWA SECTION ═══════ -->
		<section id="pwa" class="hp-pwa">
			<div class="w">
				<div class="pwa-card">
					<div class="pwa-text">
						<div class="badge">Progressive Web App</div>
						<h2>Install the bakery on your phone</h2>
						<p>Tres Marias works like a native app — fast loading, add-to-home-screen support, and browsing that stays usable even on a slow connection.</p>

						<div class="pwa-perks">
							<div>
								<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
								Add to home screen
							</div>
							<div>
								<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
								Works offline
							</div>
							<div>
								<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
								Fast repeat orders
							</div>
						</div>
					</div>

					<div class="pwa-visual">
						<div class="phone-mock">
							<div class="phone-dots"><span></span><span></span><span></span></div>
							<div class="phone-row">
								<div class="phone-row-icon">📶</div>
								<div class="phone-row-text">
									<strong>Offline cache active</strong>
									<span>Menu stays accessible</span>
								</div>
							</div>
							<div class="phone-row">
								<div class="phone-row-icon">🔔</div>
								<div class="phone-row-text">
									<strong>Pre-order reminder</strong>
									<span>Your slot opens at 9 PM</span>
								</div>
							</div>
							<div class="phone-row">
								<div class="phone-row-icon">🛵</div>
								<div class="phone-row-text">
									<strong>Rider dispatched</strong>
									<span>Arriving in 12 min</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- ═══════ CTA ═══════ -->
		<section class="cta">
			<div class="w">
				<div class="cta-box">
					<h2>Ready for freshly baked goodness?</h2>
					<p>Create an account, explore the bakery menu, and place your first order in minutes.</p>

					<div class="cta-btns">
						@auth
							<a href="{{ route('products') }}" class="btn btn-fill btn-lg">Order now</a>
						@else
							@if (Route::has('register'))
								<a href="{{ route('register') }}" class="btn btn-fill btn-lg">Create your account</a>
							@endif
							@if (Route::has('login'))
								<a href="{{ route('login') }}" class="btn btn-outline btn-lg">I already have an account</a>
							@endif
						@endauth
					</div>
				</div>
			</div>
		</section>

	</main>

	<!-- ═══════ FOOTER ═══════ -->
	<footer class="ft">
		<div class="w">
			<div class="ft-panel">
				<div>
					<strong>Tres Marias</strong>
					<p>Fresh bread, pastries, and hyper-local delivery — wrapped in a fast, installable web app.</p>
					<div class="ft-note">&copy; {{ date('Y') }} Tres Marias. Freshly baked for the neighborhood.</div>
				</div>
				<div>
					<strong>Explore</strong>
					<ul>
						<li><a href="#products">Products</a></li>
						<li><a href="#features">Features</a></li>
						<li><a href="#how">How it works</a></li>
					</ul>
				</div>
				<div>
					<strong>Account</strong>
					<ul>
						@if (Route::has('login'))
							<li><a href="{{ route('login') }}">Log in</a></li>
						@endif
						@if (Route::has('register'))
							<li><a href="{{ route('register') }}">Register</a></li>
						@endif
						<li><a href="{{ route('home') }}">Home</a></li>
					</ul>
				</div>
			</div>
		</div>
	</footer>

	<script>
		document.addEventListener('click', function(e) {
			var dd = document.getElementById('profileDropdown');
			if (dd && !dd.contains(e.target)) dd.classList.remove('open');
		});
	</script>
</body>
</html>
