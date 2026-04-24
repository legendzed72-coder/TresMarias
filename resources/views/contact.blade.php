<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#8b4513">
	<meta name="description" content="Contact Tres Marias — reach out for orders, questions, or feedback. We'd love to hear from you!">

	<title>Contact | Tres Marias Bakery</title>

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
				<a href="{{ route('contact') }}" class="active">Contact</a>
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

		<!-- ═══════ CONTACT HERO ═══════ -->
		<section class="contact-hero">
			<div class="w">
				<div class="badge">Get in touch</div>
				<h1>We'd love to <em>hear from you</em></h1>
				<p>Have a question, feedback, or a bulk order in mind? Reach out and we'll get back to you as soon as we can.</p>
			</div>
		</section>

		<!-- ═══════ CONTACT INFO CARDS ═══════ -->
		<section class="info">
			<div class="w">
				<div class="info-grid">
					<article class="info-card">
						<div class="info-icon ii-loc">📍</div>
						<h3>Visit us</h3>
						<p>123 quezon st,<br>Centro 12 APARRI CAGAYAN</p>
					</article>
					<article class="info-card">
						<div class="info-icon ii-phone">📞</div>
						<h3>Call us</h3>
						<p>+63 912 345 6789<br>+63 2 8123 4567</p>
					</article>
					<article class="info-card">
						<div class="info-icon ii-mail">✉️</div>
						<h3>Email us</h3>
						<p>hello@tresmarias.ph<br>orders@tresmarias.ph</p>
					</article>
					<article class="info-card">
						<div class="info-icon ii-hours">🕐</div>
						<h3>Store hours</h3>
						<p>Mon – Sat: 5:00 AM – 8:00 PM<br>Sun: 6:00 AM – 6:00 PM</p>
					</article>
				</div>
			</div>
		</section>

		<!-- ═══════ CONTACT FORM ═══════ -->
		<section class="contact-section">
			<div class="w contact-grid">
				<div class="contact-text">
					<h2>Send us a message</h2>
					<p>
						Whether you want to place a bulk order for an event, ask about our products, or simply share your thoughts — we're all ears. Fill out the form and our team will respond within 24 hours.
					</p>
					<p>
						For urgent orders or same-day inquiries, please call us directly. Our lines are open during store hours.
					</p>

					<div class="social-links">
						<a href="#" class="social-link" aria-label="Facebook">
							<svg fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
						</a>
						<a href="#" class="social-link" aria-label="Instagram">
							<svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
						</a>
						<a href="#" class="social-link" aria-label="TikTok">
							<svg fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
						</a>
					</div>
				</div>

				<form class="contact-form" id="contactForm" onsubmit="handleSubmit(event)">
					@csrf
					<div class="form-success" id="formSuccess">Thank you! Your message has been sent. We'll get back to you soon.</div>

					<div class="form-row">
						<div class="form-group">
							<label for="name">Full name</label>
							<input type="text" id="name" name="name" placeholder="Juan Dela Cruz" required>
						</div>
						<div class="form-group">
							<label for="email">Email address</label>
							<input type="email" id="email" name="email" placeholder="juan@example.com" required>
						</div>
					</div>

					<div class="form-group">
						<label for="subject">Subject</label>
						<select id="subject" name="subject" required>
							<option value="" disabled selected>Choose a topic...</option>
							<option value="general">General inquiry</option>
							<option value="order">Order question</option>
							<option value="bulk">Bulk / event order</option>
							<option value="feedback">Feedback</option>
							<option value="partnership">Partnership / collaboration</option>
							<option value="other">Other</option>
						</select>
					</div>

					<div class="form-group">
						<label for="message">Message</label>
						<textarea id="message" name="message" placeholder="Tell us what's on your mind..." required></textarea>
					</div>

					<button type="submit" class="btn btn-fill btn-lg" style="width:100%">
						Send message
					</button>
				</form>
			</div>
		</section>

		<!-- ═══════ CTA ═══════ -->
		<section class="cta">
			<div class="w">
				<div class="cta-box">
					<h2>Ready to order?</h2>
					<p>Skip the line and order online. Fresh bread, pastries, and more — delivered right to your doorstep.</p>

					<div class="cta-btns">
						@auth
							<a href="{{ route('products.catalog') }}" class="btn btn-fill btn-lg">Order now</a>
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
						<li><a href="{{ route('contact') }}">Contact</a></li>
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

		function handleSubmit(e) {
			e.preventDefault();
			var form = document.getElementById('contactForm');
			var success = document.getElementById('formSuccess');
			success.classList.add('show');
			form.reset();
			setTimeout(function() { success.classList.remove('show'); }, 5000);
		}
	</script>
</body>
</html>
