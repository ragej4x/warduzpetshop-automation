



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Monitor</title>
    <style>
        .warning {
            display: none;
            color: red;
        }
        .value {
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div>
        <h3>Live pH Value</h3>
        <span id="ph-value" class="value">Loading...</span>
        <span id="ph-warning" class="warning">⚠️ pH Out of Range!</span>
    </div>

    <div>
        <h3>Live Temperature</h3>
        <span id="temp-value" class="value">Loading...</span>
        <span id="temp-warning" class="warning">⚠️ High Temperature!</span>
    </div>

    <script>
        // Function to hide an image or warning
        function hideImage(imageId) {
            document.getElementById(imageId).style.display = 'none';
        }

        // Function to show an image or warning
        function showImage(imageId) {
            document.getElementById(imageId).style.display = 'inline';
        }

        // Function to fetch live data and update UI
        async function fetchLiveData() {
            try {
                const response = await fetch('live-monitor/get_live_data.php');
                const data = await response.json();

                // Update pH value
                document.getElementById('ph-value').textContent = `${data.ph} pH`;
                if (data.ph > 8.5 || data.ph < 5.5) {
                    document.getElementById('ph-value').style.color = 'red';
                    showImage('ph-warning');
                } else {
                    document.getElementById('ph-value').style.color = 'green';
                    hideImage('ph-warning');
                }

                // Update temperature value
                const tempCelsius = parseFloat(data.temperature.split(" ")[0]);
                document.getElementById('temp-value').textContent = data.temperature;
                if (tempCelsius > 30) {
                    document.getElementById('temp-value').style.color = 'red';
                    showImage('temp-warning');
                } else {
                    document.getElementById('temp-value').style.color = 'green';
                    hideImage('temp-warning');
                }
            } catch (error) {
                console.error('Error fetching live data:', error);
            }
        }

        // Fetch live data every second
        setInterval(fetchLiveData, 1000);
        fetchLiveData(); // Initial load
    </script>
</body>
</html>
