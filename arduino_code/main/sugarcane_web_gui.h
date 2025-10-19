#pragma once

const char HTML_PAGE[] PROGMEM = R"rawliteral(
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sugarcane Analyzer</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #3F4B44 0%, #1E231D 100%);
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            color: #E5E5E5;
        }
        
        .container {
            text-align: center;
            z-index: 10;
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .subtitle {
            font-size: 1.1rem;
            margin-bottom: 3rem;
            opacity: 0.9;
        }
        
        .scan-button {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: #E5E5E5;
            border: 4px solid #8B7355;
            cursor: pointer;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .scan-button:hover {
            transform: scale(1.05);
            box-shadow: 0 25px 70px rgba(0,0,0,0.4);
        }
        
        .scan-button:active {
            transform: scale(0.95);
        }
        
        .scan-button.loading {
            cursor: not-allowed;
            pointer-events: none;
        }
        
        .button-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
        
        .scan-icon {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }
        
        .scan-text {
            font-size: 1.2rem;
            font-weight: 600;
            color: #3F4B44;
        }
        
        /* Loader Animation */
        .loader {
            display: none;
            width: 60px;
            height: 60px;
            border: 6px solid #8B7355;
            border-top: 6px solid #DAA520;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        .scan-button.loading .button-content {
            display: none;
        }
        
        .scan-button.loading .loader {
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        
        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
        
        .status-message {
            margin-top: 2rem;
            font-size: 1rem;
            min-height: 24px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .status-message.show {
            opacity: 1;
        }
        
        .status-message.error {
            color: #ff6b6b;
        }
        
        .status-message.success {
            color: #DAA520;
        }
        
        /* Pulse animation for button */
        @keyframes pulse {
            0% {
                box-shadow: 0 20px 60px rgba(0,0,0,0.3), 0 0 0 0 rgba(255, 255, 255, 0.7);
            }
            70% {
                box-shadow: 0 20px 60px rgba(0,0,0,0.3), 0 0 0 20px rgba(255, 255, 255, 0);
            }
            100% {
                box-shadow: 0 20px 60px rgba(0,0,0,0.3), 0 0 0 0 rgba(255, 255, 255, 0);
            }
        }
        
        .scan-button:not(.loading) {
            animation: pulse 2s infinite;
        }
        
        /* Background particles */
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(218, 165, 32, 0.1);
            animation: float 15s infinite ease-in-out;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
            }
            50% {
                transform: translateY(-100px) translateX(50px);
            }
        }
    </style>
</head>
<body>
    <!-- Background particles -->
    <div class="particle" style="width: 80px; height: 80px; top: 10%; left: 10%; animation-delay: 0s;"></div>
    <div class="particle" style="width: 60px; height: 60px; top: 20%; right: 15%; animation-delay: 2s;"></div>
    <div class="particle" style="width: 100px; height: 100px; bottom: 15%; left: 20%; animation-delay: 4s;"></div>
    <div class="particle" style="width: 70px; height: 70px; bottom: 20%; right: 10%; animation-delay: 6s;"></div>
    
    <div class="container">
        <h1>🌾 Sugarcane Analyzer</h1>
        <p class="subtitle">Press the button to analyze sample</p>
        
        <button class="scan-button" id="scanButton" onclick="startAnalysis()">
            <div class="button-content">
                <svg class="scan-icon" viewBox="0 0 24 24" fill="none" stroke="#8B7355" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 6v6l4 2"/>
                </svg>
                <span class="scan-text">SCAN</span>
            </div>
            <div class="loader"></div>
        </button>
        
        <div class="status-message" id="statusMessage"></div>
    </div>

    <script>
        let isAnalyzing = false;

        async function startAnalysis() {
            if (isAnalyzing) return;
            
            isAnalyzing = true;
            const button = document.getElementById('scanButton');
            const statusMessage = document.getElementById('statusMessage');
            
            // Add loading state
            button.classList.add('loading');
            showStatus('Analyzing sample...', 'info');
            
            try {
                const response = await fetch('/analyze', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Analysis failed');
                }
                
                const data = await response.json();
                
                // Show success message
                showStatus('✓ Analysis complete! Redirecting...', 'success');
                
                // Wait 1.5 seconds then redirect
                setTimeout(() => {
                    window.location.href = 'https://sugarcane.bucocu.net/dashboard';
                }, 1500);
                
            } catch (error) {
                // Show error message
                showStatus('✗ Analysis failed. Please try again.', 'error');
                button.classList.remove('loading');
                isAnalyzing = false;
                
                // Clear error message after 3 seconds
                setTimeout(() => {
                    statusMessage.classList.remove('show');
                }, 3000);
            }
        }
        
        function showStatus(message, type) {
            const statusMessage = document.getElementById('statusMessage');
            statusMessage.textContent = message;
            statusMessage.className = 'status-message show ' + type;
        }
    </script>
</body>
</html>
)rawliteral";