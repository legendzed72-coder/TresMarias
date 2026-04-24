/**
 * Tres Marias — Geolocation Module
 *
 * Provides browser geolocation helpers and an Alpine.js-powered
 * Leaflet map component for picking / displaying delivery locations.
 *
 * Usage in Blade:
 *   @vite('resources/js/geolocation.js')
 *
 *   <div x-data="deliveryMap()" x-init="init()" ...>
 *       <div x-ref="map" style="height:360px;border-radius:.75rem;"></div>
 *       <input type="hidden" name="latitude"  :value="lat">
 *       <input type="hidden" name="longitude" :value="lng">
 *   </div>
 */

import Alpine from 'alpinejs';

/* ──────────────────────────────────────────────
   1.  BAKERY STORE LOCATION  (default centre)
   ────────────────────────────────────────────── */
const STORE_LAT  = 14.5995;   // Tres Marias bakery — update with real coords
const STORE_LNG  = 120.9842;
const MAX_DELIVERY_KM = 10;   // delivery radius in km

/* ──────────────────────────────────────────────
   2.  BROWSER GEOLOCATION HELPERS
   ────────────────────────────────────────────── */

/**
 * Get the user's current position via the Geolocation API.
 * Returns { lat, lng, accuracy } or throws on failure.
 */
export function getCurrentPosition(options = {}) {
    return new Promise((resolve, reject) => {
        if (!('geolocation' in navigator)) {
            return reject(new Error('Geolocation is not supported by this browser.'));
        }

        navigator.geolocation.getCurrentPosition(
            (pos) =>
                resolve({
                    lat: pos.coords.latitude,
                    lng: pos.coords.longitude,
                    accuracy: pos.coords.accuracy,
                }),
            (err) => reject(err),
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 60000,
                ...options,
            },
        );
    });
}

/**
 * Watch the user's position continuously.
 * Returns a watchId that can be passed to navigator.geolocation.clearWatch().
 */
export function watchPosition(callback, errorCallback = null) {
    if (!('geolocation' in navigator)) {
        if (errorCallback) errorCallback(new Error('Geolocation not supported.'));
        return null;
    }

    return navigator.geolocation.watchPosition(
        (pos) =>
            callback({
                lat: pos.coords.latitude,
                lng: pos.coords.longitude,
                accuracy: pos.coords.accuracy,
            }),
        errorCallback || (() => {}),
        { enableHighAccuracy: true },
    );
}

/* ──────────────────────────────────────────────
   3.  DISTANCE  (Haversine formula)
   ────────────────────────────────────────────── */

/**
 * Calculate distance in kilometres between two lat/lng points.
 */
export function distanceKm(lat1, lng1, lat2, lng2) {
    const R = 6371; // Earth radius in km
    const dLat = toRad(lat2 - lat1);
    const dLng = toRad(lng2 - lng1);
    const a =
        Math.sin(dLat / 2) ** 2 +
        Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLng / 2) ** 2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

function toRad(deg) {
    return (deg * Math.PI) / 180;
}

/**
 * Check whether a point is within the bakery's delivery radius.
 */
export function isWithinDeliveryRadius(lat, lng, maxKm = MAX_DELIVERY_KM) {
    return distanceKm(STORE_LAT, STORE_LNG, lat, lng) <= maxKm;
}

/* ──────────────────────────────────────────────
   4.  REVERSE GEOCODING  (OpenStreetMap Nominatim)
   ────────────────────────────────────────────── */

/**
 * Reverse-geocode a lat/lng pair into a human-readable address.
 * Uses the free Nominatim API (1 req/sec limit — fine for user actions).
 */
export async function reverseGeocode(lat, lng) {
    const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&addressdetails=1`;
    const res = await fetch(url, {
        headers: { 'Accept-Language': 'en' },
    });
    if (!res.ok) throw new Error('Reverse geocode failed');
    return res.json();
}

/* ──────────────────────────────────────────────
   5.  LEAFLET MAP  — lazy loader
   ────────────────────────────────────────────── */

let leafletLoaded = false;

/**
 * Dynamically load Leaflet CSS + JS from CDN if not already present.
 */
function loadLeaflet() {
    if (leafletLoaded || window.L) {
        leafletLoaded = true;
        return Promise.resolve();
    }

    return new Promise((resolve) => {
        const css = document.createElement('link');
        css.rel = 'stylesheet';
        css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
        css.integrity = 'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=';
        css.crossOrigin = '';
        document.head.appendChild(css);

        const js = document.createElement('script');
        js.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
        js.integrity = 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
        js.crossOrigin = '';
        js.onload = () => {
            leafletLoaded = true;
            resolve();
        };
        document.head.appendChild(js);
    });
}

/* ──────────────────────────────────────────────
   6.  ALPINE.JS COMPONENT  —  deliveryMap()
   ────────────────────────────────────────────── */

/**
 * Alpine data component for an interactive delivery-location picker.
 *
 * Props (HTML data-* attributes):
 *   data-initial-lat   initial marker latitude
 *   data-initial-lng   initial marker longitude
 *   data-readonly       "true" to disable marker dragging
 */
Alpine.data('deliveryMap', () => ({
    lat: null,
    lng: null,
    address: '',
    city: '',
    postalCode: '',
    withinRadius: true,
    locating: false,
    errorMsg: '',

    _map: null,
    _marker: null,
    _circle: null,

    async init() {
        const el = this.$el;
        this.lat = parseFloat(el.dataset.initialLat) || STORE_LAT;
        this.lng = parseFloat(el.dataset.initialLng) || STORE_LNG;
        const readonly = el.dataset.readonly === 'true';

        await loadLeaflet();

        // Create map
        this._map = L.map(this.$refs.map).setView([this.lat, this.lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>',
        }).addTo(this._map);

        // Store marker (bread icon)
        L.marker([STORE_LAT, STORE_LNG], {
            icon: L.divIcon({
                className: 'store-marker',
                html: '<span style="font-size:1.6rem">🍞</span>',
                iconSize: [32, 32],
                iconAnchor: [16, 16],
            }),
        })
            .addTo(this._map)
            .bindPopup('Tres Marias Bakery');

        // Delivery radius circle
        this._circle = L.circle([STORE_LAT, STORE_LNG], {
            radius: MAX_DELIVERY_KM * 1000,
            color: '#b65a2d',
            fillColor: '#b65a2d',
            fillOpacity: 0.07,
            weight: 1,
        }).addTo(this._map);

        // Delivery location marker
        this._marker = L.marker([this.lat, this.lng], {
            draggable: !readonly,
        }).addTo(this._map);

        if (!readonly) {
            this._marker.on('dragend', () => {
                const pos = this._marker.getLatLng();
                this._updatePosition(pos.lat, pos.lng);
            });

            this._map.on('click', (e) => {
                this._marker.setLatLng(e.latlng);
                this._updatePosition(e.latlng.lat, e.latlng.lng);
            });
        }

        // If no initial coords provided, try to geolocate
        if (!el.dataset.initialLat) {
            this.locateMe();
        }
    },

    /** Geolocate the user and move the marker */
    async locateMe() {
        this.locating = true;
        this.errorMsg = '';
        try {
            const pos = await getCurrentPosition();
            this._marker.setLatLng([pos.lat, pos.lng]);
            this._map.setView([pos.lat, pos.lng], 16);
            this._updatePosition(pos.lat, pos.lng);
        } catch (err) {
            this.errorMsg = 'Could not detect your location. Please place the pin manually.';
        } finally {
            this.locating = false;
        }
    },

    /** Update lat/lng, reverse-geocode, check radius */
    async _updatePosition(lat, lng) {
        this.lat = Math.round(lat * 1e8) / 1e8;
        this.lng = Math.round(lng * 1e8) / 1e8;
        this.withinRadius = isWithinDeliveryRadius(lat, lng);

        try {
            const geo = await reverseGeocode(lat, lng);
            const a = geo.address || {};
            this.address = geo.display_name || '';
            this.city =
                a.city || a.town || a.municipality || a.village || '';
            this.postalCode = a.postcode || '';
        } catch {
            // geocoding is best-effort
        }
    },

    /** Expose distance from store for templates */
    get distanceFromStore() {
        if (this.lat == null) return null;
        return distanceKm(STORE_LAT, STORE_LNG, this.lat, this.lng).toFixed(1);
    },
}));

/* ──────────────────────────────────────────────
   7.  ALPINE.JS COMPONENT  —  deliveryTracker()
   ────────────────────────────────────────────── */

/**
 * Read-only map for customers to track a delivery in real-time.
 *
 * Props:
 *   data-delivery-lat   driver latitude
 *   data-delivery-lng   driver longitude
 *   data-dest-lat       destination latitude
 *   data-dest-lng       destination longitude
 *   data-poll-url       API endpoint returning { lat, lng, status }
 */
Alpine.data('deliveryTracker', () => ({
    driverLat: null,
    driverLng: null,
    destLat: null,
    destLng: null,
    status: 'pending',
    _map: null,
    _driverMarker: null,
    _pollInterval: null,

    async init() {
        const el = this.$el;
        this.destLat = parseFloat(el.dataset.destLat) || STORE_LAT;
        this.destLng = parseFloat(el.dataset.destLng) || STORE_LNG;
        this.driverLat = parseFloat(el.dataset.deliveryLat) || this.destLat;
        this.driverLng = parseFloat(el.dataset.deliveryLng) || this.destLng;

        await loadLeaflet();

        this._map = L.map(this.$refs.map).setView([this.destLat, this.destLng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>',
        }).addTo(this._map);

        // Destination marker
        L.marker([this.destLat, this.destLng]).addTo(this._map).bindPopup('Delivery Address');

        // Driver marker
        this._driverMarker = L.marker([this.driverLat, this.driverLng], {
            icon: L.divIcon({
                className: 'driver-marker',
                html: '<span style="font-size:1.5rem">🚗</span>',
                iconSize: [28, 28],
                iconAnchor: [14, 14],
            }),
        })
            .addTo(this._map)
            .bindPopup('Driver');

        // Fit both markers in view
        this._map.fitBounds([
            [this.destLat, this.destLng],
            [this.driverLat, this.driverLng],
        ], { padding: [40, 40] });

        // Poll for driver position updates
        const pollUrl = el.dataset.pollUrl;
        if (pollUrl) {
            this._pollInterval = setInterval(() => this._poll(pollUrl), 10000);
        }
    },

    async _poll(url) {
        try {
            const { data } = await window.axios.get(url);
            if (data.lat && data.lng) {
                this.driverLat = data.lat;
                this.driverLng = data.lng;
                this._driverMarker.setLatLng([data.lat, data.lng]);
            }
            if (data.status) {
                this.status = data.status;
                if (data.status === 'delivered' || data.status === 'failed') {
                    clearInterval(this._pollInterval);
                }
            }
        } catch {
            // silent fail — will retry on next interval
        }
    },

    destroy() {
        if (this._pollInterval) clearInterval(this._pollInterval);
    },
}));

/* ──────────────────────────────────────────────
   8.  GLOBAL EXPORTS
   ────────────────────────────────────────────── */

window.TresMariaGeo = {
    getCurrentPosition,
    watchPosition,
    distanceKm,
    isWithinDeliveryRadius,
    reverseGeocode,
    STORE_LAT,
    STORE_LNG,
    MAX_DELIVERY_KM,
};
