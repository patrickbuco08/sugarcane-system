import Toastify from "toastify-js";
import "toastify-js/src/toastify.css";

document.addEventListener("DOMContentLoaded", () => {
    let currentSampleId = window.latestSampleId || null;
    let isRefreshing = false;

    async function fetchLatestSample() {
        if (isRefreshing) return;

        try {
            const response = await fetch('/latest-sample', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                console.error('Failed to fetch latest sample');
                return;
            }

            const data = await response.json();
            
            // Check if there's a new sample
            if (data.id && currentSampleId && data.id !== currentSampleId) {
                isRefreshing = true;

                // Show toast notification
                Toastify({
                    text: `New sugarcane sample detected! ${data.label || 'Sample #' + data.id}`,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #10b981, #059669)",
                    stopOnFocus: true,
                }).showToast();

                // Refresh page after 3 seconds
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            }

            // Update current ID
            if (data.id) {
                currentSampleId = data.id;
            }
        } catch (error) {
            console.error('Error fetching latest sample:', error);
        }
    }

    // Poll every 3 seconds
    const pollInterval = setInterval(fetchLatestSample, 3000);

    // Initial fetch
    fetchLatestSample();

    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        clearInterval(pollInterval);
    });
});
