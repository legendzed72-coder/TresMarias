<!-- resources/js/components/pos/PosInterface.vue -->
<template>
  <div class="pos-container">
    <!-- Header -->
    <header class="pos-header">
      <div class="logo">🥖 Bakery POS</div>
      <div class="header-actions">
        <button @click="syncOfflineData" :disabled="!isOnline || syncing" class="sync-btn">
          <span v-if="syncing">Syncing...</span>
          <span v-else-if="pendingSyncCount > 0">Sync ({{ pendingSyncCount }})</span>
          <span v-else>✓ Synced</span>
        </button>
        <div class="connection-status" :class="{ online: isOnline }">
          {{ isOnline ? 'Online' : 'OFFLINE' }}
        </div>
        <button @click="logout" class="logout-btn">Logout</button>
      </div>
    </header>

    <div class="pos-layout">
      <!-- Product Grid -->
      <div class="products-section">
        <div class="category-tabs">
          <button 
            v-for="cat in categories" 
            :key="cat.id"
            @click="selectedCategory = cat.id"
            :class="{ active: selectedCategory === cat.id }"
          >
            {{ cat.name }}
          </button>
          <button @click="selectedCategory = null" :class="{ active: !selectedCategory }">
            All
          </button>
        </div>

        <div class="products-grid">
          <div 
            v-for="product in filteredProducts" 
            :key="product.id"
            @click="addToCart(product)"
            class="product-card"
            :class="{ 'low-stock': product.stock < 5 }"
          >
            <img :src="product.image || '/placeholder.png'" :alt="product.name">
            <div class="product-info">
              <h4>{{ product.name }}</h4>
              <p class="price">₱{{ formatPrice(product.price) }}</p>
              <span class="stock">Stock: {{ product.stock }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Cart Section -->
      <div class="cart-section">
        <h3>Current Order</h3>
        
        <div v-if="cart.length === 0" class="empty-cart">
          Tap products to add to cart
        </div>
        
        <div v-else class="cart-items">
          <div v-for="(item, index) in cart" :key="index" class="cart-item">
            <div class="item-info">
              <span class="name">{{ item.name }}</span>
              <span class="price">₱{{ formatPrice(item.price * item.quantity) }}</span>
            </div>
            <div class="item-controls">
              <button @click="decrementItem(index)">-</button>
              <span>{{ item.quantity }}</span>
              <button @click="incrementItem(index)">+</button>
              <button @click="removeItem(index)" class="remove">×</button>
            </div>
          </div>
        </div>

        <div class="cart-summary">
          <div class="summary-row">
            <span>Subtotal:</span>
            <span>₱{{ formatPrice(subtotal) }}</span>
          </div>
          <div class="summary-row">
            <span>Tax (12%):</span>
            <span>₱{{ formatPrice(tax) }}</span>
          </div>
          <div class="summary-row total">
            <span>Total:</span>
            <span>₱{{ formatPrice(total) }}</span>
          </div>
        </div>

        <div class="payment-section">
          <h4>Payment Method</h4>
          <div class="payment-methods">
            <button 
              v-for="method in paymentMethods" 
              :key="method"
              @click="selectedPayment = method"
              :class="{ active: selectedPayment === method }"
            >
              {{ method }}
            </button>
          </div>

          <div v-if="selectedPayment === 'cash'" class="cash-calculator">
            <label>Amount Received:</label>
            <input 
              v-model.number="amountReceived" 
              type="number" 
              step="0.01"
              placeholder="0.00"
            >
            <div v-if="change > 0" class="change">
              Change: ₱{{ formatPrice(change) }}
            </div>
          </div>
        </div>

        <button 
          @click="processPayment" 
          :disabled="!canCheckout"
          class="checkout-btn"
        >
          Complete Sale
        </button>
      </div>
    </div>

    <!-- Receipt Modal -->
    <ReceiptModal 
      v-if="showReceipt" 
      :order="lastOrder"
      @close="showReceipt = false"
      @print="printReceipt"
    />
  </div>
</template>

<script>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { usePosStore } from '@/stores/pos';
import ReceiptModal from './ReceiptModal.vue';

export default {
  components: { ReceiptModal },
  
  setup() {
    const store = usePosStore();
    const isOnline = ref(navigator.onLine);
    const syncing = ref(false);
    
    // Cart state
    const cart = ref([]);
    const selectedPayment = ref('cash');
    const amountReceived = ref(0);
    const showReceipt = ref(false);
    const lastOrder = ref(null);
    
    // Products
    const products = ref([]);
    const categories = ref([]);
    const selectedCategory = ref(null);
    
    const paymentMethods = ['cash', 'card', 'gcash', 'maya'];

    // Computed
    const filteredProducts = computed(() => {
      if (!selectedCategory.value) return products.value;
      return products.value.filter(p => p.category_id === selectedCategory.value);
    });

    const subtotal = computed(() => 
      cart.value.reduce((sum, item) => sum + (item.price * item.quantity), 0)
    );

    const tax = computed(() => subtotal.value * 0.12);
    const total = computed(() => subtotal.value + tax.value);
    const change = computed(() => amountReceived.value - total.value);
    
    const canCheckout = computed(() => 
      cart.value.length > 0 && 
      selectedPayment.value && 
      (selectedPayment.value !== 'cash' || amountReceived.value >= total.value)
    );

    const pendingSyncCount = computed(() => store.pendingSyncCount);

    // Methods
    const loadProducts = async () => {
      try {
        const response = await fetch('/api/pos/products');
        const data = await response.json();
        products.value = data.products;
        categories.value = data.categories;
        
        // Cache for offline use
        localStorage.setItem('pos_products', JSON.stringify(data));
      } catch (e) {
        // Load from cache if offline
        const cached = localStorage.getItem('pos_products');
        if (cached) {
          const data = JSON.parse(cached);
          products.value = data.products;
          categories.value = data.categories;
        }
      }
    };

    const addToCart = (product) => {
      if (product.stock < 1) {
        alert('Out of stock!');
        return;
      }
      
      const existing = cart.value.find(item => item.id === product.id);
      if (existing) {
        if (existing.quantity < product.stock) {
          existing.quantity++;
        } else {
          alert('Maximum stock reached!');
        }
      } else {
        cart.value.push({
          id: product.id,
          name: product.name,
          price: product.price,
          quantity: 1,
          stock: product.stock
        });
      }
    };

    const incrementItem = (index) => {
      const item = cart.value[index];
      if (item.quantity < item.stock) {
        item.quantity++;
      }
    };

    const decrementItem = (index) => {
      if (cart.value[index].quantity > 1) {
        cart.value[index].quantity--;
      } else {
        removeItem(index);
      }
    };

    const removeItem = (index) => {
      cart.value.splice(index, 1);
    };

    const processPayment = async () => {
      const orderData = {
        items: cart.value.map(item => ({
          product_id: item.id,
          quantity: item.quantity,
          unit_price: item.price
        })),
        payment_method: selectedPayment.value,
        payment_status: 'paid',
        pos_device_id: localStorage.getItem('pos_device_id') || 'unknown'
      };

      try {
        let response;
        
        if (isOnline.value) {
          response = await fetch('/api/pos/orders', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: JSON.stringify(orderData)
          });
        } else {
          // Save offline
          await store.queueOfflineOrder(orderData);
          response = { 
            ok: true, 
            json: async () => ({ offline: true, order: { order_number: 'OFFLINE-' + Date.now() }})
          };
        }

        if (response.ok) {
          const result = await response.json();
          lastOrder.value = result.order;
          showReceipt.value = true;
          
          // Clear cart
          cart.value = [];
          amountReceived.value = 0;
          
          // Refresh products to get updated stock
          loadProducts();
        }
      } catch (error) {
        alert('Error processing order: ' + error.message);
      }
    };

    const syncOfflineData = async () => {
      if (!isOnline.value || store.pendingSyncCount === 0) return;
      
      syncing.value = true;
      await store.syncOfflineOrders();
      syncing.value = false;
      loadProducts();
    };

    const formatPrice = (price) => {
      return Number(price).toFixed(2);
    };

    // Network status
    const updateOnlineStatus = () => {
      isOnline.value = navigator.onLine;
      if (isOnline.value && store.pendingSyncCount > 0) {
        syncOfflineData();
      }
    };

    onMounted(() => {
      loadProducts();
      window.addEventListener('online', updateOnlineStatus);
      window.addEventListener('offline', updateOnlineStatus);
      
      // Listen for service worker messages
      navigator.serviceWorker?.addEventListener('message', (event) => {
        if (event.data.type === 'SYNC_COMPLETE') {
          alert(`Synced ${event.data.data.synced} orders`);
          loadProducts();
        }
      });
    });

    onUnmounted(() => {
      window.removeEventListener('online', updateOnlineStatus);
      window.removeEventListener('offline', updateOnlineStatus);
    });

    return {
      products,
      categories,
      selectedCategory,
      filteredProducts,
      cart,
      selectedPayment,
      paymentMethods,
      amountReceived,
      subtotal,
      tax,
      total,
      change,
      canCheckout,
      showReceipt,
      lastOrder,
      isOnline,
      syncing,
      pendingSyncCount,
      addToCart,
      incrementItem,
      decrementItem,
      removeItem,
      processPayment,
      syncOfflineData,
      formatPrice
    };
  }
};
</script>

<style scoped>
.pos-container {
  display: flex;
  flex-direction: column;
  height: 100vh;
  background: #f5f5f5;
}

.pos-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  background: #f97316;
  color: white;
}

.pos-layout {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 1rem;
  padding: 1rem;
  flex: 1;
  overflow: hidden;
}

.products-section {
  background: white;
  border-radius: 8px;
  padding: 1rem;
  overflow-y: auto;
}

.category-tabs {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 1rem;
  overflow-x: auto;
}

.category-tabs button {
  padding: 0.5rem 1rem;
  border: none;
  background: #e5e7eb;
  border-radius: 20px;
  cursor: pointer;
  white-space: nowrap;
}

.category-tabs button.active {
  background: #f97316;
  color: white;
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  gap: 1rem;
}

.product-card {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 1rem;
  cursor: pointer;
  transition: transform 0.2s;
  text-align: center;
}

.product-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.product-card.low-stock {
  border-color: #ef4444;
  background: #fef2f2;
}

.product-card img {
  width: 100%;
  height: 100px;
  object-fit: cover;
  border-radius: 4px;
  margin-bottom: 0.5rem;
}

.cart-section {
  background: white;
  border-radius: 8px;
  padding: 1rem;
  display: flex;
  flex-direction: column;
}

.cart-items {
  flex: 1;
  overflow-y: auto;
  margin: 1rem 0;
}

.cart-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.item-controls {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.item-controls button {
  width: 24px;
  height: 24px;
  border: none;
  background: #f3f4f6;
  border-radius: 4px;
  cursor: pointer;
}

.item-controls button.remove {
  background: #ef4444;
  color: white;
  margin-left: 0.5rem;
}

.cart-summary {
  border-top: 2px solid #e5e7eb;
  padding-top: 1rem;
  margin-bottom: 1rem;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.5rem;
}

.summary-row.total {
  font-size: 1.25rem;
  font-weight: bold;
  color: #f97316;
}

.payment-methods {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.payment-methods button {
  padding: 0.75rem;
  border: 1px solid #e5e7eb;
  background: white;
  border-radius: 4px;
  cursor: pointer;
  text-transform: uppercase;
  font-size: 0.875rem;
}

.payment-methods button.active {
  background: #f97316;
  color: white;
  border-color: #f97316;
}

.checkout-btn {
  width: 100%;
  padding: 1rem;
  background: #10b981;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1.125rem;
  font-weight: bold;
  cursor: pointer;
  margin-top: auto;
}

.checkout-btn:disabled {
  background: #9ca3af;
  cursor: not-allowed;
}

.connection-status {
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  background: #ef4444;
  color: white;
  font-size: 0.75rem;
  font-weight: bold;
}

.connection-status.online {
  background: #10b981;
}

.sync-btn {
  padding: 0.5rem 1rem;
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

@media (max-width: 768px) {
  .pos-layout {
    grid-template-columns: 1fr;
    grid-template-rows: 1fr auto;
  }
}
</style>