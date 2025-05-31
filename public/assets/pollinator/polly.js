class PollingManager {
    constructor({ 
        url, 
        delay = 5000, 
        failRetryCount = 3, 
        onSuccess = null, 
        onError = null 
    }) {
        if (!url) throw new Error("URL is required for polling.");

        this.url = url;
        this.delay = delay;
        this.failRetryCount = failRetryCount;
        this.onSuccess = onSuccess;
        this.onError = onError;

        this.lastFetchedData = null; // To store the last fetched data

        this.poller = new Pollinator(async () => {
            const response = await fetch(this.url);
            if (!response.ok) throw new Error("Network error");
            return await response.json();
        }, { delay: this.delay, failRetryCount: this.failRetryCount });

        this.setupListeners();
    }

    // Helper function to check if data has changed
    hasDataChanged(data) {
        return JSON.stringify(data) !== JSON.stringify(this.lastFetchedData);
    }

    setupListeners() {
        // Polling logic
        this.poller.on(Pollinator.Event.POLL, (data) => {
            if (this.hasDataChanged(data)) {
                // console.log("Received new data:", data);

                // Call onSuccess every time new data is fetched
                if (this.onSuccess) {
                    this.onSuccess(data);  // Only call onSuccess when data changes
                }

                this.lastFetchedData = data; // Update last fetched data
            } else {
                // console.log("No new data detected.");
            }
        });

        // Error handling logic
        this.poller.on(Pollinator.Event.ERROR, (error) => {
            console.error("Polling encountered an error:", error);

            // Optional backoff/retry mechanism if an error occurs
            if (this.failRetryCount > 0) {
                setTimeout(() => {
                    this.poller.start(); // Retry polling after a delay
                }, this.delay);
                this.failRetryCount--;
            }

            if (this.onError) {
                this.onError(error);  // Call the error handler every time an error occurs
            }
        });
    }

    start() {
        this.poller.start();  // Start the polling process
    }

    stop() {
        this.poller.stop();  // Stop the polling process
    }
}
