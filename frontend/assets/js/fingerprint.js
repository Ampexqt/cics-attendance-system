/**
 * Device Fingerprinting Utility
 * CICS Attendance System
 * 
 * Generates a unique fingerprint for the current device/browser
 * to enforce 1 device per account restriction.
 */

const DeviceFingerprint = {
    /**
     * Generate a unique device fingerprint
     * @returns {Promise<string>} The device fingerprint hash
     */
    async generate() {
        try {
            const components = await this.collectComponents();
            const fingerprint = await this.hashComponents(components);
            return fingerprint;
        } catch (error) {
            // Fallback to basic fingerprint
            return this.generateFallbackFingerprint();
        }
    },

    /**
     * Collect various browser/device characteristics
     * @returns {Promise<Object>} Object containing device characteristics
     */
    async collectComponents() {
        const components = {
            // Device-specific (browser-agnostic)
            deviceModel: this.getDeviceModel(),
            platform: navigator.platform,
            hardwareConcurrency: navigator.hardwareConcurrency || 0,
            deviceMemory: navigator.deviceMemory || 0,
            screenResolution: `${screen.width}x${screen.height}`,
            screenColorDepth: screen.colorDepth,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            timezoneOffset: new Date().getTimezoneOffset(),
            touchSupport: this.hasTouchSupport(),
            language: navigator.language
        };

        return components;
    },

    /**
     * Extract device model from userAgent (browser-agnostic)
     * Removes browser name/version but keeps device information
     */
    getDeviceModel() {
        const ua = navigator.userAgent;

        // Remove browser identifiers but keep device info
        let deviceInfo = ua
            .replace(/Chrome\/[\d.]+\s*/gi, '')
            .replace(/Firefox\/[\d.]+\s*/gi, '')
            .replace(/Safari\/[\d.]+\s*/gi, '')
            .replace(/Edge\/[\d.]+\s*/gi, '')
            .replace(/Edg\/[\d.]+\s*/gi, '')
            .replace(/OPR\/[\d.]+\s*/gi, '')
            .replace(/Opera\/[\d.]+\s*/gi, '')
            .replace(/Version\/[\d.]+\s*/gi, '')
            .replace(/AppleWebKit\/[\d.]+\s*/gi, '')
            .replace(/KHTML, like Gecko\s*/gi, '')
            .replace(/Gecko\/[\d]+\s*/gi, '')
            .replace(/rv:[\d.]+\s*/gi, '')
            .trim();

        return deviceInfo;
    },

    /**
     * Check if device has touch support
     */
    hasTouchSupport() {
        return ('ontouchstart' in window) ||
            (navigator.maxTouchPoints > 0) ||
            (navigator.msMaxTouchPoints > 0);
    },

    /**
     * Get canvas fingerprint
     */
    async getCanvasFingerprint() {
        try {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            canvas.width = 200;
            canvas.height = 50;

            ctx.textBaseline = 'top';
            ctx.font = '14px Arial';
            ctx.textBaseline = 'alphabetic';
            ctx.fillStyle = '#f60';
            ctx.fillRect(125, 1, 62, 20);
            ctx.fillStyle = '#069';
            ctx.fillText('CICS Attendance System', 2, 15);
            ctx.fillStyle = 'rgba(102, 204, 0, 0.7)';
            ctx.fillText('Device Fingerprint', 4, 17);

            return canvas.toDataURL();
        } catch (e) {
            return 'canvas-error';
        }
    },

    /**
     * Get WebGL fingerprint
     */
    getWebGLFingerprint() {
        try {
            const canvas = document.createElement('canvas');
            const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');

            if (!gl) return 'no-webgl';

            const debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
            if (debugInfo) {
                return gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL);
            }

            return 'webgl-available';
        } catch (e) {
            return 'webgl-error';
        }
    },

    /**
     * Get font fingerprint
     */
    async getFontFingerprint() {
        const baseFonts = ['monospace', 'sans-serif', 'serif'];
        const testFonts = [
            'Arial', 'Verdana', 'Times New Roman', 'Courier New',
            'Georgia', 'Palatino', 'Garamond', 'Comic Sans MS',
            'Trebuchet MS', 'Impact'
        ];

        const testString = 'mmmmmmmmmmlli';
        const testSize = '72px';
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        const baseFontWidths = {};
        baseFonts.forEach(baseFont => {
            ctx.font = testSize + ' ' + baseFont;
            baseFontWidths[baseFont] = ctx.measureText(testString).width;
        });

        const detectedFonts = [];
        testFonts.forEach(font => {
            for (let baseFont of baseFonts) {
                ctx.font = testSize + ' ' + font + ',' + baseFont;
                const width = ctx.measureText(testString).width;
                if (width !== baseFontWidths[baseFont]) {
                    detectedFonts.push(font);
                    break;
                }
            }
        });

        return detectedFonts.join(',');
    },

    /**
     * Get audio fingerprint
     */
    async getAudioFingerprint() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return 'no-audio-context';

            const context = new AudioContext();
            const oscillator = context.createOscillator();
            const analyser = context.createAnalyser();
            const gainNode = context.createGain();
            const scriptProcessor = context.createScriptProcessor(4096, 1, 1);

            gainNode.gain.value = 0; // Mute
            oscillator.connect(analyser);
            analyser.connect(scriptProcessor);
            scriptProcessor.connect(gainNode);
            gainNode.connect(context.destination);

            oscillator.start(0);

            return new Promise((resolve) => {
                scriptProcessor.onaudioprocess = function (event) {
                    const output = event.outputBuffer.getChannelData(0);
                    let sum = 0;
                    for (let i = 0; i < output.length; i++) {
                        sum += Math.abs(output[i]);
                    }
                    oscillator.stop();
                    scriptProcessor.disconnect();
                    resolve(sum.toString());
                };
            });
        } catch (e) {
            return 'audio-error';
        }
    },

    /**
     * Get installed plugins
     */
    getPlugins() {
        if (!navigator.plugins || navigator.plugins.length === 0) {
            return 'no-plugins';
        }

        const plugins = [];
        for (let i = 0; i < navigator.plugins.length; i++) {
            plugins.push(navigator.plugins[i].name);
        }
        return plugins.slice(0, 5).join(','); // Limit to first 5
    },

    /**
     * Check local storage availability
     */
    hasLocalStorage() {
        try {
            return !!window.localStorage;
        } catch (e) {
            return false;
        }
    },

    /**
     * Check session storage availability
     */
    hasSessionStorage() {
        try {
            return !!window.sessionStorage;
        } catch (e) {
            return false;
        }
    },

    /**
     * Hash the collected components into a fingerprint
     */
    async hashComponents(components) {
        const componentString = JSON.stringify(components);

        // Use SubtleCrypto API for SHA-256 hash
        if (window.crypto && window.crypto.subtle) {
            try {
                const encoder = new TextEncoder();
                const data = encoder.encode(componentString);
                const hashBuffer = await window.crypto.subtle.digest('SHA-256', data);
                const hashArray = Array.from(new Uint8Array(hashBuffer));
                const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
                return hashHex;
            } catch (e) {
                // SubtleCrypto error handled silently
            }
        }

        // Fallback to simple hash
        return this.simpleHash(componentString);
    },

    /**
     * Simple hash function fallback
     */
    simpleHash(str) {
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            const char = str.charCodeAt(i);
            hash = ((hash << 5) - hash) + char;
            hash = hash & hash; // Convert to 32bit integer
        }
        return Math.abs(hash).toString(16).padStart(16, '0');
    },

    /**
     * Generate a basic fallback fingerprint
     */
    generateFallbackFingerprint() {
        const simple = [
            this.getDeviceModel(),
            navigator.platform,
            screen.width + 'x' + screen.height,
            new Date().getTimezoneOffset()
        ].join('|');

        return this.simpleHash(simple);
    }
};

// Export for use in other scripts
window.DeviceFingerprint = DeviceFingerprint;
