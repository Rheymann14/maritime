<!DOCTYPE html>
<html>
<head>
<<<<<<< Updated upstream
    <?php
    session_start();

    if(!isset($_SESSION['username'])) {
        header("location: login");
        exit();
    }

    function fetch_location_data() {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://maritimeobt.com/api/get-location",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Content-Type: application/json",
                "Authorization: Bearer " . $_SESSION['token']
            ]
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "<script>alert('Error: " . $err . "');</script>";
            return null;
        } else {
            return json_decode($response, true);
        }
    }

    $data = fetch_location_data();
    if ($data) {
        $lat = $data["lat"];
        $lng = $data["lng"];
    }
    ?>

    <title>Location Tracker</title>
    <!-- Favicons -->
    <link href="assets/img/finallogo.png" rel="icon">
    <link href="assets/img/finallogo.png" rel="apple-touch-icon">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7MNHKamCiEM7coJY2uaJ6l2cNfctqA7Q"></script>

    <script>
        let map;
        let marker;
        let infoWindow;

        function initMap(lat, lng) {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: { lat: lat, lng: lng }
            });

            const icon = {
                url: "assets/img/finalLogo.png", // Replace with your actual image path
                scaledSize: new google.maps.Size(30, 30), // Adjust size as needed
                anchor: new google.maps.Point(15, 15) // Set anchor point for centering
            };

            marker = new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: map,
                icon: icon
            });

            const infoWindowContent = document.createElement('div');
            infoWindowContent.innerHTML = `
                <b>Name:</b> Juan Dela Cruz<br>
                <b>Vessel:</b> Marina Vessel<br>
                <b>Shipping Company:</b> Marina
                `;

            infoWindow = new google.maps.InfoWindow({
                content: infoWindowContent
            });

            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });
        }

        function animateMarker(marker, newPosition) {
            const duration = 1000; // Animation duration in milliseconds
            const steps = 20; // Number of animation steps
            const stepTime = duration / steps;
            const startPosition = marker.getPosition();
            let step = 0;

            function animate() {
                step++;
                if (step > steps) return;

                const lat = startPosition.lat() + (newPosition.lat() - startPosition.lat()) * (step / steps);
                const lng = startPosition.lng() + (newPosition.lng() - startPosition.lng()) * (step / steps);

                marker.setPosition({ lat, lng });
                setTimeout(animate, stepTime);
            }

            animate();
        }

        function updateMap(lat, lng) {
            const newPosition = new google.maps.LatLng(lat, lng);
            animateMarker(marker, newPosition);
            // map.setCenter(newPosition);
        }

        function fetchLocation() {
            fetch('https://maritimeobt.com/api/get-location', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + '<?php echo $_SESSION['token']; ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data) {
                    const lat = data.lat;
                    const lng = data.lng;
                    updateMap(lat, lng);
                }
            })
            .catch(error => console.error('Error fetching location:', error));
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Initialize the map with the server-provided location
            const initialLat = <?php echo json_encode($lat); ?>;
            const initialLng = <?php echo json_encode($lng); ?>;
            initMap(initialLat, initialLng);

            // Update the map every 2 seconds
            setInterval(fetchLocation, 2000);
        });
    </script>
</head>
<body>
    <?= $lat ?>
    <?= $lng ?>
    <div id="map" style="height: 400px; width: 100%;"></div>
=======
  <title>Phone Location Tracker</title>

  <script src="https://maps.googleapis.com/maps/api/js?AIzaSyD7MNHKamCiEM7coJY2uaJ6l2cNfctqA7Q"></script>

  <script>
    // Set the update interval in milliseconds (here, 5 seconds)
    const updateInterval = 60000;

    function showPosition(position) {
      const latitude = position.coords.latitude;
      const longitude = position.coords.longitude;

      // Send the latitude and longitude to your PHP script (e.g., using AJAX)
      sendLocationData(latitude, longitude);

      // (Optional) Display a basic map with the current location
      const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        center: { lat: latitude, lng: longitude },
      });

          // Create a custom icon using the circle image
          const icon = {
        url: "assets/img/finalLogo.png", // Replace with your actual image path
        scaledSize: new google.maps.Size(30, 30), // Adjust size as needed
        anchor: new google.maps.Point(15, 15), // Set anchor point for centering
      };

      const marker = new google.maps.Marker({
        position: { lat: latitude, lng: longitude },
        map: map,
        icon: icon,
      });

      // Create the popup content with title, spacing, and your desired information
      const infoWindowContent = document.createElement('div');

      infoWindowContent.innerHTML = `
        <b>Name:</b> Juan Dela Cruz<br>
        <b>Vessel:</b> Marina Vessel<br>
        <b>Shipping Company:</b> Marina
      `;

      // Create the info window
      const infoWindow = new google.maps.InfoWindow({
        content: infoWindowContent
      });

      // Add click listener to the marker to show the popup
      marker.addListener('click', () => {
        infoWindow.open(map, marker);
      });
    }

    function handleError(error) {
      const errorMessages = {
        1: "Permission denied",
        2: "Position unavailable",
        3: "Timeout",
      };
      const errorMessage = errorMessages[error.code] || "Unknown error";
      alert("Error: " + errorMessage);
    }

    function sendLocationData(latitude, longitude) {
      // Implement logic to send data to your PHP script (e.g., using AJAX)
      // This might involve an XMLHttpRequest or a library like Fetch API
      console.log("Latitude:", latitude, "Longitude:", longitude);
      // Replace with your actual PHP script URL
      fetch("your_php_script.php", {
        method: "POST",
        body: JSON.stringify({ latitude: latitude, longitude: longitude }),
      })
        .then(response => response.text())
        .then(data => console.log(data))
        .catch(error => console.error(error));
    }

    // Get location on page load and update on interval
    navigator.geolocation.watchPosition(showPosition, handleError, { enableHighAccuracy: true });
    setInterval(navigator.geolocation.getCurrentPosition, updateInterval);
  </script>

</head>
<body>
  <div id="map" style="height: 400px; width: 100%;"></div>
>>>>>>> Stashed changes
</body>
</html>
