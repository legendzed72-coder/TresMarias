document.addEventListener('alpine:init', () => {
    Alpine.data('staffDashboard', () => ({
        orderSearch: '',
        showModal: false,
        modalStatus: '',
        modalContent: '',
        loading: false,

        init() {
            // Auto-refresh data every 30 seconds
            setInterval(() => {
                this.refreshData();
            }, 30000);
        },

        refreshData() {
            // Show loading state on refresh button
            const refreshBtn = document.querySelector('[x-on\\:click="refreshData()"]');
            if (refreshBtn) {
                const originalText = refreshBtn.innerHTML;
                refreshBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>' + window.translations?.refreshing || 'Refreshing...';
                refreshBtn.disabled = true;

                // Reload the page to refresh all data
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
        },

        showOrderModal(status) {
            this.showModal = true;
            this.modalStatus = status;
            this.loading = true;
            this.modalContent = '';

            // For now, show a placeholder message since the API endpoint doesn't exist
            setTimeout(() => {
                this.loading = false;
                this.modalContent = `
                    <div class="text-center py-8">
                        <svg class="mx-auto w-12 h-12 text-bark-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-2 text-muted">${window.translations?.orderDetailsHere || 'Order details will be displayed here'}</p>
                        <p class="text-sm text-muted mt-1">${window.translations?.comingSoon || 'This feature is coming soon'}</p>
                    </div>
                `;
            }, 500);
        }
    }));
});