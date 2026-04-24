<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#8b4513">
	<meta name="description" content="Tres Marias — Browse our fresh bakery products. Order breads, pastries, and more for pickup or delivery.">

	<title>Products | Tres Marias Bakery</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

	<meta name="csrf-token" content="{{ csrf_token() }}">

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
		<!-- ═══════ PRODUCTS ═══════ -->
		<section class="section">
			<div class="w">
				<div class="section-heading">
					<h2>Our Products</h2>
					<p>Freshly baked breads and pastries, made with love every morning.</p>
				</div>

				@php
					$products = App\Models\Product::where('is_active', true)->with('category')->get();
				@endphp

				@if($products->isEmpty())
					<div class="no-products">
						<p>No products available at the moment. Please check back later!</p>
					</div>
				@else
					<div class="product-grid">
						@foreach($products as $product)
							<div class="p-card">
								<div class="p-card-img">
									@if($product->image_url)
										<img src="{{ Str::startsWith($product->image_url, ['http://', 'https://']) ? $product->image_url : asset(ltrim($product->image_url, '/')) }}" alt="{{ $product->name }}">
									@else
										<div style="width: 100%; height: 100%; background: #f7ecdc; display: flex; align-items: center; justify-content: center;">
											<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 48px; height: 48px; color: #b65a2d;">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
											</svg>
										</div>
									@endif
									@if($product->category)
										<div class="p-badge-cat">{{ $product->category->name }}</div>
									@endif
									@if($product->stock_quantity <= 0)
										<div class="p-card-oos">
											<span>Out of Stock</span>
										</div>
									@endif
								</div>
								<div class="p-card-body">
									<h3>{{ $product->name }}</h3>
									<p class="p-desc">{{ Str::limit($product->description, 100) }}</p>
									<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.75rem;">
										<span style="font-size: 1.1rem; font-weight: 700; color: var(--accent);">₱{{ number_format($product->price, 2) }}</span>
										@if($product->available_for_preorder)
											<div class="p-badge p-badge-preorder">
												<svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
												</svg>
												Pre-order
											</div>
										@endif
									</div>
									@if($product->stock_quantity > 0)
										<div style="margin-top: 1rem; display: flex; flex-direction: column; gap: 0.5rem;">
											@if($product->available_for_preorder)
												<form class="preorder-form" data-product-id="{{ $product->id }}" style="display: flex; gap: 0.5rem; align-items: center;">
													<input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" style="width: 60px; padding: 0.25rem; border: 1px solid #d1d5db; border-radius: 0.25rem; text-align: center;" required>
													<button type="submit" style="flex: 1; background-color: var(--accent); color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; border: none; cursor: pointer; font-weight: 500;">Pre-order</button>
													@auth
														<button type="button" onclick="toggleFavorite('{{ $product->id }}', this)" class="like-btn" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; padding: 0.25rem;" title="Add to favorites">
															<span data-product-id="{{ $product->id }}" class="heart-icon">🤍</span>
														</button>
													@endauth
												</form>
											@endif
											<button type="button" onclick="showOrderModal('{{ $product->id }}', '{{ $product->name }}', '{{ $product->price }}')" style="background-color: #10b981; color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; border: none; cursor: pointer; font-weight: 500; width: 100%;">
												🛍️ Order Now
											</button>
										</div>
									@elseif($product->stock_quantity <= 0 && $product->available_for_preorder)
										<div style="margin-top: 1rem; color: #dc2626; font-size: 0.875rem;">Out of stock</div>
									@else
										@auth
											<div style="margin-top: 1rem;">
										<button type="button" onclick="toggleFavorite('{{ $product->id }}', this)" class="like-btn" style="background-color: #f3f4f6; color: #374151; padding: 0.5rem 1rem; border-radius: 0.25rem; border: none; cursor: pointer; font-weight: 500; width: 100%;">
													<span data-product-id="{{ $product->id }}" class="heart-icon">🤍</span> Add to Wishlist
												</button>
											</div>
										@endauth
									@endif
								</div>
							</div>
						@endforeach
					</div>
				@endif
			</div>
		</section>
	</main>

	<!-- ═══════ FOOTER ═══════ -->
	<footer class="ft ft--simple">
		<div class="w">
			<div class="ft-brand">
				<img src="{{ asset('images/logo.png') }}" alt="Tres Marias" style="width:40px;height:40px;border-radius:50%;object-fit:cover;margin-right:0.5rem;">
				Tres Marias
			</div>
			<p>&copy; {{ date('Y') }} Tres Marias Bakery. All rights reserved.</p>
		</div>
	</footer>

	{{-- Order Modal --}}
	<div id="orderModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
		<div style="background: white; border-radius: 0.5rem; padding: 2rem; max-width: 500px; width: 90%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
			<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
				<h2 style="font-size: 1.5rem; font-weight: 700; margin: 0;" id="orderModalTitle">Order Now</h2>
				<button onclick="closeOrderModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">✕</button>
			</div>
			
			<form id="quickOrderForm" style="gap: 1rem; display: flex; flex-direction: column;">
				<div style="margin-bottom: 1rem;">
					<label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Quantity</label>
					<input type="number" id="orderQuantity" min="1" value="1" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.25rem; font-size: 1rem;">
				</div>

				<div style="margin-bottom: 1rem;">
					<label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Fulfillment Type</label>
					<div style="display: flex; gap: 1rem;">
						<label style="display: flex; align-items: center; cursor: pointer;">
							<input type="radio" name="fulfillment" value="pickup" checked style="margin-right: 0.5rem;">
							🏪 Pickup
						</label>
						<label style="display: flex; align-items: center; cursor: pointer;">
							<input type="radio" name="fulfillment" value="delivery" style="margin-right: 0.5rem;">
							🚚 Delivery (+₱50)
						</label>
					</div>
				</div>

				<div id="deliverySection" style="display: none; margin-bottom: 1rem;">
					<label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Delivery Address</label>
					<input type="text" id="deliveryAddress" placeholder="Enter your delivery address" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.25rem; font-size: 1rem; margin-bottom: 1rem;">
					
					<div style="margin-bottom: 1rem;">
						<label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Delivery Location Map</label>
						<div id="deliveryMap" style="width: 100%; height: 250px; border: 2px solid #d1d5db; border-radius: 0.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.875rem; position: relative; overflow: hidden;">
							<div style="text-align: center;">
								<div style="font-size: 2rem; margin-bottom: 0.5rem;">📍</div>
								<div>Your store location on map</div>
								<div style="font-size: 0.75rem; margin-top: 0.5rem; opacity: 0.9;">Enter address to see delivery zone</div>
							</div>
						</div>
					</div>
				</div>

				<div style="margin-bottom: 1rem;">
					<label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Special Instructions</label>
					<textarea id="orderNotes" placeholder="Any special requests?" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.25rem; font-size: 1rem; min-height: 80px; font-family: inherit; resize: vertical;"></textarea>
				</div>

				<div style="background: #f3f4f6; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
					<div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
						<span>Subtotal:</span>
						<span id="orderSubtotal">₱0.00</span>
					</div>
					<div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
						<span>Delivery:</span>
						<span id="orderDelivery">₱0.00</span>
					</div>
					<div style="border-top: 1px solid #d1d5db; padding-top: 0.5rem; display: flex; justify-content: space-between; font-weight: 700; font-size: 1.1rem;">
						<span>Total:</span>
						<span id="orderTotal">₱0.00</span>
					</div>
				</div>

				<div style="display: flex; gap: 1rem;">
					<button type="button" onclick="closeOrderModal()" style="flex: 1; background: #e5e7eb; color: #374151; padding: 0.75rem; border-radius: 0.25rem; border: none; cursor: pointer; font-weight: 600;">Cancel</button>
					<button type="submit" style="flex: 1; background-color: #10b981; color: white; padding: 0.75rem; border-radius: 0.25rem; border: none; cursor: pointer; font-weight: 600;">Place Order</button>
				</div>
			</form>
		</div>
	</div>

	@auth
		<script>
			// Order modal functionality
			let currentOrderProduct = { id: null, name: '', price: 0 };

			function showOrderModal(productId, productName, productPrice) {
				currentOrderProduct = { id: productId, name: productName, price: productPrice };
				document.getElementById('orderModalTitle').textContent = `Order ${productName}`;
				document.getElementById('orderQuantity').value = '1';
				document.getElementById('orderNotes').value = '';
				document.querySelector('input[name="fulfillment"][value="pickup"]').checked = true;
				updateOrderTotal();
				document.getElementById('orderModal').style.display = 'flex';
			}
		</script>
	@else
		<script>
			function showOrderModal() {
				alert('Please log in to place an order');
				window.location.href = '{{ route("login") }}';
			}
		</script>
	@endauth
	<script>
		// Shared order modal functionality

		function closeOrderModal() {
			document.getElementById('orderModal').style.display = 'none';
		}

		function updateOrderTotal() {
			const quantity = parseInt(document.getElementById('orderQuantity').value) || 0;
			const fulfillment = document.querySelector('input[name="fulfillment"]:checked').value;
			const deliveryFee = fulfillment === 'delivery' ? 50 : 0;
			
			const subtotal = currentOrderProduct.price * quantity;
			const total = subtotal + deliveryFee;

			document.getElementById('orderSubtotal').textContent = '₱' + subtotal.toFixed(2);
			document.getElementById('orderDelivery').textContent = '₱' + deliveryFee.toFixed(2);
			document.getElementById('orderTotal').textContent = '₱' + total.toFixed(2);

			// Show/hide delivery section
			const deliverySection = document.getElementById('deliverySection');
			if (fulfillment === 'delivery') {
				deliverySection.style.display = 'block';
			} else {
				deliverySection.style.display = 'none';
			}
		}

		document.getElementById('orderQuantity').addEventListener('change', updateOrderTotal);
		document.querySelectorAll('input[name="fulfillment"]').forEach(input => {
			input.addEventListener('change', updateOrderTotal);
		});

		// Delivery map functionality
		const deliveryAddressInput = document.getElementById('deliveryAddress');
		const deliveryMap = document.getElementById('deliveryMap');

		// Store location (Tres Marias, adjust these coordinates as needed)
		const storeLocation = {
			name: 'Tres Marias - Cakes & Pastries',
			lat: 14.3067, // Sample coordinates (Manila, Philippines)
			lng: 121.0325,
			address: 'Tres Marias Bakery'
		};

		function updateDeliveryMap() {
			const address = deliveryAddressInput.value.trim();
			const hasAddress = address.length > 0;

			if (hasAddress) {
				// Create a simple map visualization
				deliveryMap.innerHTML = `
					<div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative;">
						<div style="text-align: center; color: white; z-index: 10;">
							<div style="font-size: 2rem; margin-bottom: 0.5rem;">📍</div>
							<div style="font-weight: 600; margin-bottom: 0.25rem;">Delivery Location Set</div>
							<div style="font-size: 0.875rem;">Address: <strong>${address}</strong></div>
							<div style="font-size: 0.75rem; margin-top: 0.5rem; opacity: 0.9;">Estimated delivery fee: ₱50</div>
						</div>
						<div style="position: absolute; bottom: 10px; right: 10px; background: white; padding: 0.5rem 1rem; border-radius: 0.25rem; font-size: 0.75rem; color: #374151; cursor: pointer;" onclick="window.open('https://maps.google.com/?q=${encodeURIComponent(address)}', '_blank')">
							🗺️ View on Maps
						</div>
					</div>
				`;
			} else {
				deliveryMap.innerHTML = `
					<div style="text-align: center;">
						<div style="font-size: 2rem; margin-bottom: 0.5rem;">📍</div>
						<div>Your store location on map</div>
						<div style="font-size: 0.75rem; margin-top: 0.5rem; opacity: 0.9;">Enter address to see delivery zone</div>
					</div>
				`;
			}
		}

		deliveryAddressInput.addEventListener('input', updateDeliveryMap);
		deliveryAddressInput.addEventListener('change', updateDeliveryMap);

		document.getElementById('quickOrderForm').addEventListener('submit', async (e) => {
			e.preventDefault();

			const quantity = parseInt(document.getElementById('orderQuantity').value);
			const fulfillment = document.querySelector('input[name="fulfillment"]:checked').value;
			const notes = document.getElementById('orderNotes').value;
			const deliveryAddress = document.getElementById('deliveryAddress').value;

			// Validate delivery address if delivery is selected
			if (fulfillment === 'delivery' && !deliveryAddress.trim()) {
				alert('Please enter a delivery address.');
				return;
			}

			try {
				const response = await fetch('/api/quick-order', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
					},
					body: JSON.stringify({
						items: [{
							product_id: currentOrderProduct.id,
							quantity: quantity
						}],
						fulfillment_type: fulfillment,
						delivery_address: fulfillment === 'delivery' ? deliveryAddress : null,
						special_instructions: notes
					})
				});

				const data = await response.json();

				if (response.ok) {
					alert(`Order placed successfully! Order #: ${data.order_number}`);
					closeOrderModal();
					window.location.href = '{{ route("my-orders") }}';
				} else {
					alert('Error: ' + (data.message || 'Failed to place order'));
				}
			} catch (error) {
				console.error('Error:', error);
				alert('An error occurred. Please try again.');
			}
		});

		// Favorite/Like functionality
		async function toggleFavorite(productId, button) {
			try {
				const response = await fetch(`/api/favorites/${productId}/toggle`, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
					}
				});

				const data = await response.json();
				const heartIcon = button.querySelector('.heart-icon');
				
				if (data.is_liked) {
					heartIcon.textContent = '❤️';
					button.style.backgroundColor = '#fee2e2';
				} else {
					heartIcon.textContent = '🤍';
					button.style.backgroundColor = '';
				}
			} catch (error) {
				console.error('Error:', error);
				alert('Failed to update favorite');
			}
		}

		// Load favorite states on page load
		async function loadFavoriteStates() {
			try {
				const response = await fetch('/api/favorites', {
					headers: { 'Accept': 'application/json' },
					credentials: 'same-origin'
				});

				if (!response.ok) return;

				const favorites = await response.json();
				const favoriteIds = favorites.map(p => p.id);

				document.querySelectorAll('.heart-icon').forEach(icon => {
					const productId = parseInt(icon.dataset.productId);
					if (favoriteIds.includes(productId)) {
						icon.textContent = '❤️';
						icon.parentElement.style.backgroundColor = '#fee2e2';
					}
				});
			} catch (error) {
				console.error('Error loading favorites:', error);
			}
		}

		document.addEventListener('DOMContentLoaded', function() {
			loadFavoriteStates();

			const preorderForms = document.querySelectorAll('.preorder-form');

			preorderForms.forEach(form => {
				form.addEventListener('submit', async function(e) {
					e.preventDefault();

					const productId = this.dataset.productId;
					const quantity = this.querySelector('input[name="quantity"]').value;

					try {
						const response = await fetch('/api/preorders', {
							method: 'POST',
							headers: {
								'Content-Type': 'application/json',
								'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
							},
							body: JSON.stringify({
								items: [{
									product_id: productId,
									quantity: parseInt(quantity)
								}],
								fulfillment_type: 'pickup'
							})
						});

						const data = await response.json();

						if (response.ok) {
							alert(`Preorder created successfully! Order number: ${data.order_number}`);
							// Optionally redirect to orders page
							// window.location.href = '{{ route("my-orders") }}';
						} else if (response.status === 401) {
							alert('Please log in to place an order.');
							window.location.href = '{{ route("login") }}';
						} else {
							alert('Error creating preorder: ' + (data.message || 'Unknown error'));
						}
					} catch (error) {
						console.error('Error:', error);
						alert('An error occurred while creating the preorder. Please try again.');
					}
				});
			});
		});
	</script>

</body>
</html></content>
<parameter name="filePath">c:\xampp\htdocs\TresMarias\resources\views\product.blade.php