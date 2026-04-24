<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#8b4513">
	<meta name="description" content="About Tres Marias — learn our story, meet the people behind your favorite neighborhood bakery, and discover what makes our bread special.">

	<title>About | Tres Marias Bakery</title>

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
				<a href="{{ route('about') }}" class="active">About</a>
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

		<!-- ═══════ ABOUT HERO ═══════ -->
		<section class="about-hero">
			<div class="w">
				<div class="badge">Our story</div>
				<h1>The heart behind <em>Tres Marias</em></h1>
				<p>What started as a family kitchen has grown into the neighborhood's favorite bakery — serving freshly baked bread, pastries, and warm smiles every single day.</p>
			</div>
		</section>

		<!-- ═══════ OUR STORY ═══════ -->
		<section class="story">
			<div class="w story-grid">
				<div class="story-img">
					<img src="{{ asset('images/08c28709-c91b-41dd-a68a-064a9bee01a7.jpg') }}" alt="Tres Marias Bakery" loading="eager">
				</div>
				<div class="story-text">
					<h2>From a family kitchen to your neighborhood</h2>
					<p>
						Tres Marias was born from a simple love of baking. Named after the three pillars of our craft — quality ingredients, time-honored recipes, and genuine care — we started with just an oven and a dream of bringing fresh, affordable bread to every home in the community.
					</p>
					<p>
						Every morning before dawn, our bakers fire up the ovens to prepare the day's fresh batch. From soft ensaymadas to crispy Spanish bread, each product is made using recipes passed down through generations, with only the finest local ingredients.
					</p>
					<p>
						Today, Tres Marias has grown beyond the counter. With our online ordering platform and neighborhood delivery service, we bring the warmth of the bakery straight to your doorstep — because everyone deserves to enjoy bread that's still warm from the oven.
					</p>
				</div>
			</div>
		</section>

		<!-- ═══════ OUR VALUES ═══════ -->
		<section class="values">
			<div class="w">
				<div class="values-head">
					<div class="badge">What we stand for</div>
					<h2>Baked with purpose</h2>
					<p>Every loaf we make is guided by these values — they're the secret ingredients behind everything at Tres Marias.</p>
				</div>

				<div class="values-grid">
					<article class="value-card">
						<div class="value-icon vi-heart">❤️</div>
						<h3>Made with love</h3>
						<p>Every product is crafted by hand with attention and care, just like homemade baking at its best.</p>
					</article>
					<article class="value-card">
						<div class="value-icon vi-wheat">🌾</div>
						<h3>Quality ingredients</h3>
						<p>We source fresh, local ingredients and never cut corners — because great bread starts with great flour.</p>
					</article>
					<article class="value-card">
						<div class="value-icon vi-hands">🤝</div>
						<h3>Community first</h3>
						<p>We're proud to be your neighborhood bakery. We keep prices affordable and give back whenever we can.</p>
					</article>
					<article class="value-card">
						<div class="value-icon vi-clock">⏰</div>
						<h3>Fresh every morning</h3>
						<p>Our ovens start before sunrise so you always get bread baked today — never yesterday's leftovers.</p>
					</article>
					<article class="value-card">
						<div class="value-icon vi-home">🏠</div>
						<h3>Delivered to your door</h3>
						<p>We bring the bakery to you with fast, reliable neighborhood delivery right to your doorstep.</p>
					</article>
					<article class="value-card">
						<div class="value-icon vi-star">⭐</div>
						<h3>Always improving</h3>
						<p>From new flavors to better service, we listen to our customers and keep raising the bar.</p>
					</article>
				</div>
			</div>
		</section>

		<!-- ═══════ OUR TEAM ═══════ -->
		<section class="team">
			<div class="w">
				<div class="team-head">
					<div class="badge">The people</div>
					<h2>Meet the team</h2>
					<p>Behind every loaf is a dedicated team that wakes up early, works hard, and loves what they do.</p>
				</div>

				<div class="team-grid">
					<div class="team-card">
						<div class="team-avatar">👩‍🍳</div>
						<h3>Head Baker</h3>
						<div class="role">Master of the Oven</div>
						<p>Leads the baking team every morning, perfecting recipes and ensuring every batch meets our standards.</p>
					</div>
					<div class="team-card">
						<div class="team-avatar">🧑‍💼</div>
						<h3>Operations</h3>
						<div class="role">Keeping Things Running</div>
						<p>Manages inventory, deliveries, and day-to-day operations so everything flows smoothly from oven to customer.</p>
					</div>
					<div class="team-card">
						<div class="team-avatar">🛵</div>
						<h3>Delivery Riders</h3>
						<div class="role">Neighborhood Heroes</div>
						<p>Our friendly riders ensure your orders arrive fresh and warm, rain or shine, across the neighborhood.</p>
					</div>
				</div>
			</div>
		</section>

		<!-- ═══════ CTA ═══════ -->
		<section class="cta">
			<div class="w">
				<div class="cta-box">
					<h2>Taste the Tres Marias difference</h2>
					<p>Join our growing community of bread lovers. Order online and experience freshly baked goodness delivered to your door.</p>

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
						<li><a href="{{ route('products.catalog') }}">Products</a></li>
						<li><a href="{{ route('about') }}">About</a></li>
						<li><a href="{{ route('home') }}#how">How it works</a></li>
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