import { Chart, registerables } from 'chart.js';
import { leaf, leafBg, gold, goldBg, bark, barkBg, muted, grid, fontFamily } from './chart-colors';

Chart.register(...registerables);

// Format relative time (e.g., "2 days ago") - updates in real-time
function formatRelativeTime(date) {
    if (!date) return '—';
    
    const now = new Date();
    const then = new Date(date);
    const seconds = Math.floor((now - then) / 1000);
    
    if (seconds < 60) return 'just now';
    const minutes = Math.floor(seconds / 60);
    if (minutes < 60) return `${minutes}m ago`;
    const hours = Math.floor(minutes / 60);
    if (hours < 24) return `${hours}h ago`;
    const days = Math.floor(hours / 24);
    if (days < 7) return `${days}d ago`;
    const weeks = Math.floor(days / 7);
    if (weeks < 4) return `${weeks}w ago`;
    const months = Math.floor(days / 30);
    return `${months}mo ago`;
}

function formatCurrency(amount) {
    if (amount === null || amount === undefined) return '₱0.00';
    return `₱${parseFloat(amount).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

// Alpine.js component for customer analytics page
window.customerAnalytics = () => ({
    recentSearch: '',
    recentCustomers: [],
    loading: false,
    recentLoading: true,
    refreshInterval: null,
    timeUpdateInterval: null,
    connectionStatus: 'connecting', // 'connecting', 'connected', 'error'
    lastUpdateTime: null,
    errorMessage: null,
    totalSpentDisplay: 0,
    totalOrdersDisplay: 0,
    previousTotalSpent: 0,
    updateAnimation: false,
    
    init() {
        console.log('🚀 Alpine.js Component Initialized');
        this.recentLoading = true;
        this.connectionStatus = 'connecting';
        
        // Force initial data load
        this.loadRecentCustomers().catch(err => {
            console.error('Initial load error:', err);
        });
        
        // Auto-refresh data every 10 seconds for real-time monitoring
        this.refreshInterval = setInterval(() => {
            console.log('Auto-refreshing customer data...');
            this.loadRecentCustomers();
        }, 10000);
        
        // Update relative times every 5 seconds for better real-time feel
        this.timeUpdateInterval = setInterval(() => this.updateFormattedTimes(), 5000);
    },
    
    getTotalSpent() {
        return this.recentCustomers.reduce((sum, customer) => sum + (parseFloat(customer.total_spent) || 0), 0);
    },
    
    getTotalOrders() {
        return this.recentCustomers.reduce((sum, customer) => sum + (parseInt(customer.total_orders) || 0), 0);
    },
    
    animateValue(startValue, endValue, duration = 1000) {
        const startTime = performance.now();
        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            this.totalSpentDisplay = startValue + (endValue - startValue) * progress;
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                this.totalSpentDisplay = endValue;
            }
        };
        requestAnimationFrame(animate);
    },
    
    async loadRecentCustomers() {
        try {
            this.connectionStatus = 'connecting';
            this.errorMessage = null;
            
            console.log('📡 Fetching recent customers...');
            
            const response = await fetch('/api/admin/customers/recent', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                credentials: 'include'
            });
            
            console.log('📡 Response status:', response.status);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('API Error Response:', errorText);
                throw new Error(`API Error ${response.status}: ${response.statusText}`);
            }
            
            const result = await response.json();
            console.log('✓ API Response:', result);
            
            if (!result.success) {
                throw new Error(result.message || 'Failed to fetch recent customers');
            }
            
            const data = result.data || [];
            
            // Check if data has changed
            const dataChanged = this.hasDataChanged(data);
            
            // Transform data and add formatted dates
            this.recentCustomers = data.map(customer => ({
                id: customer.id,
                name: customer.name || 'Unknown',
                email: customer.email || '',
                total_orders: parseInt(customer.total_orders) || 0,
                total_spent: parseFloat(customer.total_spent) || 0,
                last_order_at: customer.last_order_at,
                total_spent_formatted: formatCurrency(customer.total_spent),
                last_order_formatted: formatRelativeTime(customer.last_order_at),
                isNew: false
            }));
            
            // Calculate new totals
            const newTotalSpent = this.getTotalSpent();
            const newTotalOrders = this.getTotalOrders();
            
            console.log('💰 Total Spent:', newTotalSpent, '📦 Total Orders:', newTotalOrders);
            
            // Animate if amount changed
            if (newTotalSpent !== this.previousTotalSpent && this.previousTotalSpent > 0) {
                this.updateAnimation = true;
                this.animateValue(this.previousTotalSpent, newTotalSpent, 800);
                this.previousTotalSpent = newTotalSpent;
                setTimeout(() => this.updateAnimation = false, 800);
            } else {
                this.totalSpentDisplay = newTotalSpent;
                this.previousTotalSpent = newTotalSpent;
            }
            
            this.totalOrdersDisplay = newTotalOrders;
            
            // Mark newly added customers
            if (dataChanged && this.recentCustomers.length > 0) {
                this.recentCustomers[0].isNew = true;
            }
            
            this.lastUpdateTime = new Date();
            this.connectionStatus = 'connected';
            
            console.log('✓ Loaded:', this.recentCustomers.length, 'customers');
        } catch (error) {
            console.error('✗ Error:', error.message, error);
            this.connectionStatus = 'error';
            this.errorMessage = error.message;
            
            // Try to recover on next interval
            if (this.recentCustomers.length === 0) {
                this.recentLoading = false;
            }
        } finally {
            this.recentLoading = false;
        }
    },
    
    hasDataChanged(newData) {
        if (this.recentCustomers.length !== newData.length) {
            return true;
        }
        if (this.recentCustomers.length === 0) {
            return false;
        }
        return this.recentCustomers[0].id !== newData[0]?.id;
    },
    
    updateFormattedTimes() {
        // Update relative time formatting for all customers without API call
        if (this.recentCustomers.length > 0) {
            this.recentCustomers = this.recentCustomers.map(customer => ({
                ...customer,
                last_order_formatted: formatRelativeTime(customer.last_order_at)
            }));
        }
    },
    
    async refreshData() {
        this.loading = true;
        try {
            await this.loadRecentCustomers();
        } finally {
            this.loading = false;
        }
    },
    
    getFilteredCustomers() {
        if (!this.recentSearch) return this.recentCustomers;
        
        const search = this.recentSearch.toLowerCase();
        return this.recentCustomers.filter(customer => 
            customer.name.toLowerCase().includes(search) || 
            customer.email.toLowerCase().includes(search)
        );
    },
    
    getConnectionStatusColor() {
        switch (this.connectionStatus) {
            case 'connected': return 'bg-leaf-500';
            case 'error': return 'bg-red-500';
            default: return 'bg-yellow-500';
        }
    },
    
    getConnectionStatusText() {
        switch (this.connectionStatus) {
            case 'connected': return '✓ Connected';
            case 'error': return '✗ Error';
            default: return '⏳ Connecting...';
        }
    },
    
    getLastUpdateText() {
        if (!this.lastUpdateTime) return 'Loading...';
        const now = new Date();
        const diff = Math.floor((now - this.lastUpdateTime) / 1000);
        if (diff < 5) return 'Just now';
        if (diff < 60) return `${diff}s ago`;
        if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
        return 'A while ago';
    },
    
    formatTotalSpent() {
        return `₱${this.totalSpentDisplay.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    },
    
    destroy() {
        if (this.refreshInterval) clearInterval(this.refreshInterval);
        if (this.timeUpdateInterval) clearInterval(this.timeUpdateInterval);
    }
});

document.addEventListener('DOMContentLoaded', async function () {
    try {
        const response = await fetch('/api/admin/customers/chart-data');
        if (!response.ok) throw new Error('Failed to fetch chart data');
        const data = await response.json();

    // Customer Growth Bar Chart
    const growthCanvas = document.getElementById('customerGrowthChart');
    if (growthCanvas) {
        new Chart(growthCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: data.growthLabels,
                datasets: [{
                    label: 'New Customers',
                    data: data.growthData,
                    backgroundColor: leafBg,
                    borderColor: leaf,
                    borderWidth: 2,
                    borderRadius: 8,
                    barPercentage: 0.6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: muted, font: { family: fontFamily, size: 11 } },
                        grid: { color: grid }
                    },
                    x: {
                        ticks: { color: muted, font: { family: fontFamily, size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Order Frequency Doughnut Chart
    const freqCanvas = document.getElementById('frequencyChart');
    if (freqCanvas) {
        new Chart(freqCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: data.frequencyLabels,
                datasets: [{
                    data: data.frequencyData,
                    backgroundColor: [
                        goldBg,
                        leafBg,
                        barkBg,
                        'rgba(138, 181, 133, 0.5)',
                        'rgba(232, 196, 160, 0.6)',
                    ],
                    borderColor: [
                        gold,
                        leaf,
                        bark,
                        '#8ab585',
                        '#e8c4a0',
                    ],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: muted, font: { family: fontFamily, size: 12 }, padding: 15, usePointStyle: true }
                    }
                }
            }
        });
    }
    } catch (error) {
        console.error('Error loading chart data:', error);
    }
});
