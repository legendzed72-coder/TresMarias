<x-app-layout>
    <div class="min-h-screen bg-cream-50 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-bark-900">{{ __('My Wishlist') }}</h1>
                <p class="text-bark-600 mt-2">{{ __('Your favorite products') }}</p>
            </div>

            {{-- Empty State --}}
            <div id="emptyState" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-bark-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-bark-900">{{ __('No favorites yet') }}</h3>
                <p class="mt-2 text-bark-600">{{ __('Start adding your favorite products') }}</p>
                <a href="{{ route('products') }}" class="mt-4 inline-block px-6 py-2 bg-bark-300 text-cream-50 rounded-lg hover:bg-bark-400 transition">
                    {{ __('Browse Products') }}
                </a>
            </div>

            {{-- Products Grid --}}
            <div id="wishlistContainer" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Products will be loaded here --}}
            </div>
        </div>
    </div>
</x-app-layout>

<script>
async function loadWishlist() {
    try {
        const res = await fetch('/api/favorites', {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin'
        });

        if (!res.ok) throw new Error('Failed to load wishlist');
        
        const products = await res.json();
        const container = document.getElementById('wishlistContainer');
        const emptyState = document.getElementById('emptyState');

        if (products.length === 0) {
            container.classList.add('hidden');
            emptyState.classList.remove('hidden');
            return;
        }

        container.classList.remove('hidden');
        emptyState.classList.add('hidden');

        container.innerHTML = products.map(product => `
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                <div class="aspect-square bg-cream-100 overflow-hidden">
                    <img src="${product.image_url || '/placeholder.png'}" alt="${product.name}" class="w-full h-full object-cover hover:scale-105 transition">
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-bark-900 mb-1">${product.name}</h3>
                    <p class="text-sm text-bark-600 mb-3 line-clamp-2">${product.description || ''}</p>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-lg font-bold text-bark-900">₱${parseFloat(product.price).toFixed(2)}</span>
                        <span class="text-xs px-2 py-1 rounded-full ${product.stock_quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${product.stock_quantity > 0 ? 'In Stock' : 'Out of Stock'}
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="removeFromWishlist(${product.id})" class="flex-1 px-3 py-2 text-sm bg-red-500 text-white rounded hover:bg-red-600 transition">
                            ❤️ Remove
                        </button>
                        <button onclick="addToCart(${product.id})" class="flex-1 px-3 py-2 text-sm bg-bark-300 text-cream-50 rounded hover:bg-bark-400 transition">
                            🛒 Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    } catch (e) {
        console.error('Error loading wishlist:', e);
    }
}

async function removeFromWishlist(productId) {
    try {
        const res = await fetch(`/api/favorites/${productId}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin'
        });

        if (!res.ok) throw new Error('Failed to remove');
        await loadWishlist();
    } catch (e) {
        console.error('Error:', e);
        alert('{{ __("Failed to remove from wishlist") }}');
    }
}

function addToCart(productId) {
    alert('Add to cart feature coming soon!');
}

// Load wishlist on page load
document.addEventListener('DOMContentLoaded', loadWishlist);
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
