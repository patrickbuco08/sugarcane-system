#pragma once

// API Configuration
#define API_BASE_URL "x"
#define API_SECRET "x"

// WiFi Configuration
#define WIFI_SSID "x"
#define WIFI_PASSWORD "x"

// Static IP Configuration (set USE_STATIC_IP to true to enable)
#define USE_STATIC_IP true
#define STATIC_IP IPAddress(192, 168, 1, 100)    // Change to your desired IP
#define GATEWAY IPAddress(192, 168, 1, 1)        // Your router's IP
#define SUBNET IPAddress(255, 255, 255, 0)       // Subnet mask
#define DNS_PRIMARY IPAddress(8, 8, 8, 8)        // Google DNS (optional)