#pragma once

#include <WebServer.h>
#include <sugarcane_web_gui.h>

// External server instance (defined in main.ino)
extern WebServer server;

// ==================== Handler Functions ====================

// Root endpoint - Device info
void handleInitialEndpoint() {
  server.send(200, "text/plain", "Sugarcane Juice Analyzer v1.0");
}

void handleAnalyzeSample() {
    
}

String htmlPage() {
  return String(HTML_PAGE);
}

void handleRoot() {
  server.send(200, "text/html", htmlPage());  // Send the HTML page content
}
    
