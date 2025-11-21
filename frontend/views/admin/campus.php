<?php
require_once __DIR__ . '/../../../auth_check.php';
require_role('admin');
$activePage = 'campus';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Campus Restriction - CICS Attendance System</title>
  <link rel="stylesheet" href="../../assets/css/base/variables.css">
  <link rel="stylesheet" href="../../assets/css/pages/admin.css">
  <link rel="stylesheet" href="../../assets/css/main.css">
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <style>
    /* ========================================
       LAYOUT BUILDER - RESPONSIVE GRID SYSTEM
       ======================================== */
    
    /* Base Layout - Mobile First (320px+) */
    .two-column-layout {
      display: grid;
      grid-template-columns: 1fr;
      gap: 1rem;
      margin-bottom: 1.5rem;
      align-items: stretch;
    }
    
    .right-column {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    
    /* ========================================
       MAP CONTAINER - RESPONSIVE
       ======================================== */
    
    .map-card {
      display: flex;
      flex-direction: column;
      height: 100%;
      min-height: 400px;
    }
    
    .map-card .card-body {
      display: flex;
      flex-direction: column;
      flex: 1;
      padding: 1rem;
    }
    
    #campusMap {
      flex: 1;
      min-height: 350px;
      width: 100%;
      border-radius: var(--border-radius);
      z-index: 1;
      position: relative;
    }
    
    .map-container {
      position: relative;
      flex: 1;
      display: flex;
      flex-direction: column;
      z-index: 1;
    }
    
    /* Ensure Leaflet controls don't overlap header */
    .leaflet-top,
    .leaflet-bottom {
      z-index: 1 !important;
    }
    
    .leaflet-pane {
      z-index: 1 !important;
    }
    
    .map-loading {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
      z-index: 1000;
      background: rgba(255, 255, 255, 0.95);
      padding: 1.5rem;
      border-radius: var(--border-radius);
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    /* ========================================
       CURRENT SETTINGS CARD - RESPONSIVE
       ======================================== */
    
    .current-settings-card {
      height: auto;
    }
    
    .current-settings-card .card-body {
      padding: 1rem;
    }
    
    .settings-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 0.75rem;
      margin-top: 1rem;
    }
    
    .setting-item {
      background: var(--color-bg-secondary);
      padding: 0.875rem;
      border-radius: 8px;
      border: 1px solid var(--color-border);
      text-align: center;
    }
    
    .setting-label {
      font-size: 0.7rem;
      color: var(--color-text-muted);
      margin-bottom: 0.5rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-weight: 500;
    }
    
    .setting-value {
      font-size: 1rem;
      font-weight: 700;
      font-family: 'Courier New', monospace;
      color: var(--color-primary);
      word-break: break-all;
    }
    
    /* ========================================
       GPS FORM CARD - RESPONSIVE
       ======================================== */
    
    .gps-form-card {
      height: auto;
      flex: 1;
    }
    
    .gps-form-card .card-body {
      padding: 1rem;
    }
    
    .form-field {
      margin-bottom: 1rem;
    }
    
    .form-field:last-of-type {
      margin-bottom: 0;
    }
    
    .radius-slider-container {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
      margin-top: 0.5rem;
    }
    
    .radius-slider {
      width: 100%;
    }
    
    .radius-value {
      width: 100%;
      text-align: center;
      font-weight: 700;
      color: var(--color-primary);
      font-size: 1.25rem;
      padding: 0.5rem;
      background: var(--color-bg-secondary);
      border-radius: var(--border-radius);
    }
    
    .form-actions {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
      margin-top: 1.5rem;
      padding-top: 1.5rem;
      border-top: 1px solid var(--color-border);
    }
    
    .btn-get-location,
    .btn-save-settings {
      width: 100%;
    }
    
    /* ========================================
       MEDIA QUERIES - RESPONSIVE BREAKPOINTS
       ======================================== */
    
    /* Small Tablets (480px+) */
    @media (min-width: 480px) {
      .settings-grid {
        grid-template-columns: repeat(3, 1fr);
      }
      
      .setting-item {
        text-align: left;
      }
      
      .radius-slider-container {
        flex-direction: row;
        align-items: center;
      }
      
      .radius-value {
        min-width: 80px;
        width: auto;
        text-align: center;
      }
      
      .form-actions {
        flex-direction: row;
      }
      
      .btn-get-location,
      .btn-save-settings {
        flex: 1;
        width: auto;
      }
    }
    
    /* Tablets (768px+) */
    @media (min-width: 768px) {
      .two-column-layout {
        gap: 1.25rem;
        margin-bottom: 2rem;
      }
      
      .right-column {
        gap: 1.25rem;
      }
      
      .map-card {
        min-height: 450px;
      }
      
      #campusMap {
        min-height: 400px;
      }
      
      .map-card .card-body,
      .current-settings-card .card-body,
      .gps-form-card .card-body {
        padding: 1.25rem;
      }
      
      .map-loading {
        padding: 2rem;
      }
      
      .setting-value {
        font-size: 1.125rem;
      }
    }
    
    /* Large Tablets / Small Desktops (992px+) */
    @media (min-width: 992px) {
      .two-column-layout {
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
      }
      
      .map-card {
        min-height: 500px;
      }
      
      #campusMap {
        min-height: 450px;
      }
    }
    
    /* Desktops (1200px+) */
    @media (min-width: 1200px) {
      .two-column-layout {
        grid-template-columns: 1.2fr 0.8fr;
        gap: 1.5rem;
      }
      
      .map-card {
        min-height: 550px;
      }
      
      #campusMap {
        min-height: 500px;
      }
      
      .map-card .card-body,
      .current-settings-card .card-body,
      .gps-form-card .card-body {
        padding: 1.5rem;
      }
      
      .settings-grid {
        gap: 1rem;
      }
      
      .setting-item {
        padding: 1rem;
      }
      
      .setting-label {
        font-size: 0.75rem;
      }
    }
    
    /* Large Desktops (1400px+) */
    @media (min-width: 1400px) {
      .two-column-layout {
        grid-template-columns: 1.3fr 0.7fr;
      }
      
      .map-card {
        min-height: 600px;
      }
      
      #campusMap {
        min-height: 550px;
      }
    }
    
    /* Extra Large Desktops (1600px+) */
    @media (min-width: 1600px) {
      .two-column-layout {
        grid-template-columns: 1.4fr 0.6fr;
        max-width: 1800px;
        margin-left: auto;
        margin-right: auto;
      }
    }
    
    /* ========================================
       PRINT STYLES
       ======================================== */
    
    @media print {
      .two-column-layout {
        grid-template-columns: 1fr;
      }
      
      .form-actions {
        display: none;
      }
      
      #campusMap {
        min-height: 400px;
        page-break-inside: avoid;
      }
    }
    
    
    .btn-save {
      flex: 1;
    }
  </style>
</head>
<body>
  <div class="main-layout">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
      <?php include 'includes/header.php'; ?>

      <div class="main-body">
        <div class="page-heading">
          <div>
            <h1 class="page-title">Campus Restrictions</h1>
            <p class="page-subtitle">Set campus GPS location and attendance radius</p>
          </div>
        </div>

        <!-- Two Column Layout -->
        <div class="two-column-layout">
          <!-- Left Column: Map -->
          <div class="card map-card">
            <div class="card-body">
              <h3 class="card-title">Campus Map</h3>
              <p class="text-muted" style="margin-bottom: 1rem;">Drag the marker to set the campus center. The blue circle shows the attendance coverage area.</p>
              
              <div class="map-container">
                <div class="map-loading" id="mapLoading">
                  <div class="spinner"></div>
                  <p>Loading map...</p>
                </div>
                <div id="campusMap"></div>
              </div>
            </div>
          </div>

          <!-- Right Column: Stacked Containers -->
          <div class="right-column">
            <!-- Current Settings Display -->
            <div class="card current-settings-card">
              <div class="card-body">
                <h3 class="card-title">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle; margin-right: 0.5rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                  </svg>
                  Current Settings
                </h3>
                <div class="settings-grid">
                  <div class="setting-item">
                    <div class="setting-label">Latitude</div>
                    <div class="setting-value" id="displayLatitude">—</div>
                  </div>
                  <div class="setting-item">
                    <div class="setting-label">Longitude</div>
                    <div class="setting-value" id="displayLongitude">—</div>
                  </div>
                  <div class="setting-item">
                    <div class="setting-label">Radius</div>
                    <div class="setting-value" id="displayRadius">—</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- GPS Location Settings Form -->
            <div class="card gps-form-card">
              <div class="card-body">
                <h3 class="card-title">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle; margin-right: 0.5rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 6.627-5.373 12-12 12s-12-5.373-12-12 5.373-12 12-12 12 5.373 12 12z" />
                  </svg>
                  GPS Location Settings
                </h3>
                
                <form id="campusSettingsForm">
              <div class="form-grid">
                <div class="form-field form-group">
                  <label for="campusLatitude">Campus Latitude <span class="text-danger">*</span></label>
                  <input type="number" id="campusLatitude" name="campus_latitude" class="form-control" step="0.000001" min="-90" max="90" required>
                  <small class="text-muted">Range: -90 to 90</small>
                </div>
                <div class="form-field form-group">
                  <label for="campusLongitude">Campus Longitude <span class="text-danger">*</span></label>
                  <input type="number" id="campusLongitude" name="campus_longitude" class="form-control" step="0.000001" min="-180" max="180" required>
                  <small class="text-muted">Range: -180 to 180</small>
                </div>
              </div>

              <div class="form-field form-group">
                <label for="campusRadius">Attendance Radius (meters) <span class="text-danger">*</span></label>
                <div class="radius-slider-container">
                  <input type="range" id="campusRadius" name="campus_radius" class="radius-slider" min="10" max="1000" step="10" value="100">
                  <span class="radius-value" id="radiusValue">100m</span>
                </div>
                <small class="text-muted">Students must be within this radius to mark attendance</small>
              </div>

              <div class="form-actions">
                <button type="button" class="btn btn-outline btn-get-location" id="getCurrentLocationBtn">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 6.627-5.373 12-12 12s-12-5.373-12-12 5.373-12 12-12 12 5.373 12 12z" />
                  </svg>
                  Get My Location
                </button>
                <button type="submit" class="btn btn-primary btn-save" id="saveSettingsBtn">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                  </svg>
                  Save Settings
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>

  <?php include 'includes/scripts.php'; ?>
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
    (function() {
      const API_BASE = '/cics-attendance-system/backend/api';
      
      let map, marker, circle;
      const state = {
        latitude: 7.1117,
        longitude: 122.0735,
        radius: 100
      };

      const elements = {
        campusLatitude: document.getElementById('campusLatitude'),
        campusLongitude: document.getElementById('campusLongitude'),
        campusRadius: document.getElementById('campusRadius'),
        radiusValue: document.getElementById('radiusValue'),
        displayLatitude: document.getElementById('displayLatitude'),
        displayLongitude: document.getElementById('displayLongitude'),
        displayRadius: document.getElementById('displayRadius'),
        getCurrentLocationBtn: document.getElementById('getCurrentLocationBtn'),
        saveSettingsBtn: document.getElementById('saveSettingsBtn'),
        campusSettingsForm: document.getElementById('campusSettingsForm'),
        mapLoading: document.getElementById('mapLoading')
      };

      document.addEventListener('DOMContentLoaded', init);

      function init() {
        initializeMap();
        attachEvents();
        loadCampusSettings();
      }

      function initializeMap() {
        // Initialize Leaflet map
        map = L.map('campusMap').setView([state.latitude, state.longitude], 16);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '© OpenStreetMap contributors',
          maxZoom: 19
        }).addTo(map);

        // Add draggable marker
        marker = L.marker([state.latitude, state.longitude], {
          draggable: true,
          title: 'Campus Center'
        }).addTo(map);

        // Add radius circle
        circle = L.circle([state.latitude, state.longitude], {
          radius: state.radius,
          color: '#3b82f6',
          fillColor: '#3b82f6',
          fillOpacity: 0.2,
          weight: 2
        }).addTo(map);

        // Update coordinates when marker is dragged
        marker.on('dragend', function(e) {
          const position = marker.getLatLng();
          updateCoordinates(position.lat, position.lng, state.radius);
        });

        // Hide loading indicator
        setTimeout(() => {
          elements.mapLoading.style.display = 'none';
        }, 1000);
      }

      function attachEvents() {
        // Radius slider
        elements.campusRadius.addEventListener('input', (e) => {
          const radius = parseInt(e.target.value);
          elements.radiusValue.textContent = radius + 'm';
          state.radius = radius;
          updateCircle();
          updateDisplay();
        });

        // Form inputs
        elements.campusLatitude.addEventListener('change', (e) => {
          const lat = parseFloat(e.target.value);
          if (lat >= -90 && lat <= 90) {
            updateCoordinates(lat, state.longitude, state.radius);
          }
        });

        elements.campusLongitude.addEventListener('change', (e) => {
          const lng = parseFloat(e.target.value);
          if (lng >= -180 && lng <= 180) {
            updateCoordinates(state.latitude, lng, state.radius);
          }
        });

        // Get current location button
        elements.getCurrentLocationBtn.addEventListener('click', getCurrentLocation);

        // Form submission
        elements.campusSettingsForm.addEventListener('submit', handleSaveSettings);
      }

      async function loadCampusSettings() {
        try {
          const response = await fetch(`${API_BASE}/admin/settings/campus`, {
            credentials: 'include'
          });
          const result = await response.json();

          if (!response.ok || !result.success) {
            throw new Error(result.message || 'Failed to load settings');
          }

          const settings = result.data;
          updateCoordinates(
            settings.campus_latitude,
            settings.campus_longitude,
            settings.campus_radius
          );
        } catch (error) {
          Toast.error('Unable to load campus settings');
          console.error(error);
        }
      }

      function updateCoordinates(lat, lng, radius) {
        state.latitude = lat;
        state.longitude = lng;
        state.radius = radius;

        // Update form inputs
        elements.campusLatitude.value = lat.toFixed(6);
        elements.campusLongitude.value = lng.toFixed(6);
        elements.campusRadius.value = radius;
        elements.radiusValue.textContent = radius + 'm';

        // Update map
        marker.setLatLng([lat, lng]);
        circle.setLatLng([lat, lng]);
        circle.setRadius(radius);
        map.setView([lat, lng], map.getZoom());

        // Update display
        updateDisplay();
      }

      function updateCircle() {
        circle.setRadius(state.radius);
      }

      function updateDisplay() {
        elements.displayLatitude.textContent = state.latitude.toFixed(6);
        elements.displayLongitude.textContent = state.longitude.toFixed(6);
        elements.displayRadius.textContent = state.radius + ' meters';
      }

      function getCurrentLocation() {
        if (!navigator.geolocation) {
          Toast.error('Geolocation is not supported by your browser');
          return;
        }

        elements.getCurrentLocationBtn.disabled = true;
        elements.getCurrentLocationBtn.innerHTML = '<span class="spinner" style="width: 16px; height: 16px;"></span> Getting precise location...';

        let watchId;
        let bestPosition = null;
        let attempts = 0;
        const maxAttempts = 5; // Try to get 5 readings
        const maxWaitTime = 15000; // Maximum 15 seconds

        // GPS options for maximum accuracy
        const gpsOptions = {
          enableHighAccuracy: true,  // Use GPS if available
          timeout: 5000,             // 5 seconds per attempt
          maximumAge: 0              // No cached positions
        };

        // Set timeout to stop watching after maxWaitTime
        const timeoutId = setTimeout(() => {
          if (watchId) {
            navigator.geolocation.clearWatch(watchId);
          }
          
          if (bestPosition) {
            // Use the best position we got
            applyPosition(bestPosition);
          } else {
            Toast.error('Could not get accurate location. Please try again.');
            resetLocationButton();
          }
        }, maxWaitTime);

        // Watch position to get multiple readings
        watchId = navigator.geolocation.watchPosition(
          (position) => {
            attempts++;
            const accuracy = position.coords.accuracy;

            // Keep the most accurate position
            if (!bestPosition || accuracy < bestPosition.coords.accuracy) {
              bestPosition = position;
            }

            // If we got a very accurate reading (< 20m) or reached max attempts, use it
            if (accuracy < 20 || attempts >= maxAttempts) {
              clearTimeout(timeoutId);
              navigator.geolocation.clearWatch(watchId);
              applyPosition(bestPosition);
            } else {
              // Update button with current accuracy
              elements.getCurrentLocationBtn.innerHTML = `<span class="spinner" style="width: 16px; height: 16px;"></span> Improving accuracy... (${Math.round(accuracy)}m)`;
            }
          },
          (error) => {
            clearTimeout(timeoutId);
            if (watchId) {
              navigator.geolocation.clearWatch(watchId);
            }

            let errorMessage = 'Unable to get your location';
            switch(error.code) {
              case error.PERMISSION_DENIED:
                errorMessage = 'Location permission denied. Please allow location access in your browser settings.';
                break;
              case error.POSITION_UNAVAILABLE:
                errorMessage = 'Location information is unavailable. Make sure GPS is enabled.';
                break;
              case error.TIMEOUT:
                errorMessage = 'Location request timed out. Please try again.';
                break;
            }
            Toast.error(errorMessage);
            resetLocationButton();
          },
          gpsOptions
        );

        function applyPosition(position) {
          const accuracy = position.coords.accuracy;
          updateCoordinates(
            position.coords.latitude,
            position.coords.longitude,
            state.radius
          );
          
          let accuracyMessage = `Location updated! Accuracy: ${Math.round(accuracy)}m`;
          if (accuracy < 20) {
            accuracyMessage += ' (Excellent)';
            Toast.success(accuracyMessage);
          } else if (accuracy < 50) {
            accuracyMessage += ' (Good)';
            Toast.success(accuracyMessage);
          } else if (accuracy < 100) {
            accuracyMessage += ' (Fair)';
            Toast.success(accuracyMessage);
          } else if (accuracy < 1000) {
            accuracyMessage += ' (Poor - try again for better accuracy)';
            Toast.warning(accuracyMessage);
          } else {
            // Very poor accuracy (likely desktop/IP-based geolocation)
            accuracyMessage += ' (Poor - try again for better accuracy)';
            Toast.warning(accuracyMessage);
            Toast.info(
              'For best accuracy:\n' +
              '• Use a mobile device with GPS, OR\n' +
              '• Enter coordinates manually from Google Maps',
              { duration: 8000 }
            );
          }
          
          resetLocationButton();
        }

        function resetLocationButton() {
          elements.getCurrentLocationBtn.disabled = false;
          elements.getCurrentLocationBtn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 6.627-5.373 12-12 12s-12-5.373-12-12 5.373-12 12-12 12 5.373 12 12z" />
            </svg>
            Get My Location`;
        }
      }

      async function handleSaveSettings(event) {
        event.preventDefault();

        if (!FormValidator.validate(elements.campusSettingsForm)) {
          return;
        }

        const payload = {
          campus_latitude: parseFloat(elements.campusLatitude.value),
          campus_longitude: parseFloat(elements.campusLongitude.value),
          campus_radius: parseInt(elements.campusRadius.value)
        };

        elements.saveSettingsBtn.disabled = true;
        elements.saveSettingsBtn.innerHTML = 'Saving...';

        try {
          const response = await fetch(`${API_BASE}/admin/settings/campus`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify(payload)
          });

          const result = await response.json();

          if (!response.ok || !result.success) {
            const errorMessage = result.errors ? Object.values(result.errors).flat().join(', ') : result.message;
            throw new Error(errorMessage || 'Failed to save settings');
          }

          Toast.success('Campus settings saved successfully');
        } catch (error) {
          Toast.error(error.message || 'Unable to save settings');
        } finally {
          elements.saveSettingsBtn.disabled = false;
          elements.saveSettingsBtn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
            Save Settings`;
        }
      }
    })();
  </script>
</body>
</html>
