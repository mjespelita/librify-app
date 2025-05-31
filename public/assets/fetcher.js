let intervalId;
let lastFetchedData = null;
let isRequestInProgress = false; // Track if there's an ongoing request

function fetchData() {
    if (isRequestInProgress) {
        console.log('Skipping fetch as another request is still in progress.');
        return; // Prevent new fetch if a request is still in progress
    }

    isRequestInProgress = true;  // Set the flag indicating a request is in progress

    fetch('/users')
        .then(response => response.json())
        .then(data => {
            if (JSON.stringify(data) !== JSON.stringify(lastFetchedData)) {
                console.log('Fetched new data:', data);
                // Update last fetched data
                lastFetchedData = data;
                // Process the data as needed (e.g., update the UI)
            } else {
                console.log('No new data to fetch.');
            }

            // After a successful fetch, stop the interval to prevent too many requests
            clearInterval(intervalId);

            // Optionally, restart the interval after a delay (e.g., 5 seconds)
            setTimeout(startFetching, 5000); // Restart after 5 seconds
        })
        .catch(error => {
            console.error('API error:', error);
            clearInterval(intervalId); // Stop interval if there's an error

            // Optionally, restart the interval after a delay if there's an error
            setTimeout(startFetching, 5000);
        })
        .finally(() => {
            isRequestInProgress = false;  // Reset the flag once the request completes
        });
}

function startFetching() {
    // Start fetching the data at regular intervals
    intervalId = setInterval(fetchData, 5000); // Fetch data every 10 seconds
}

// Start the process by calling the startFetching function
startFetching();