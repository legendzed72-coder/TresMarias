/**
 * User Orders Page Alpine.js Component
 */
function ordersPage(initialOrders = []) {
    return {
        filter: 'all',
        selectedOrder: null,
        orders: initialOrders,

        get filteredOrders() {
            if (this.filter === 'all') {
                return this.orders;
            }
            return this.orders.filter(order => order.status === this.filter);
        },

        formatDate(date) {
            return new Date(date).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        },

        formatDateTime(date) {
            return new Date(date).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        capitalizeStatus(status) {
            return status.charAt(0).toUpperCase() + status.slice(1).toLowerCase();
        },

        capitalizeType(type) {
            if (type === 'pos') return 'POS';
            return type.charAt(0).toUpperCase() + type.slice(1).toLowerCase();
        },

        getStatusColor(status) {
            const colors = {
                'pending': 'bg-gold-100 text-gold-700',
                'processing': 'bg-blue-100 text-blue-700',
                'completed': 'bg-leaf-100 text-leaf-700',
                'cancelled': 'bg-red-100 text-red-700',
                'failed': 'bg-red-100 text-red-700',
            };
            return colors[status] || 'bg-bark-100 text-bark-700';
        },

        getStatusDot(status) {
            const colors = {
                'pending': 'bg-gold-400',
                'processing': 'bg-blue-400',
                'completed': 'bg-leaf-400',
                'cancelled': 'bg-red-400',
                'failed': 'bg-red-400',
            };
            return colors[status] || 'bg-bark-300';
        },

        reorderItems(order) {
            alert(`Reordering items from ${order.order_number}. This would typically add items to cart.`);
        },

        downloadInvoice(order) {
            alert(`Downloading invoice for ${order.order_number}`);
        }
    };
}

// Register globally for Alpine
window.ordersPage = ordersPage;
