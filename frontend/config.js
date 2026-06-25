// Frontend runtime configuration.
// Set `window.API_BASE` to the full backend base URL (no trailing slash),
// for example:
//   window.API_BASE = 'https://api.smart-dairy.example.com';

// By default we leave it undefined so the app will attempt the relative
// backend path used in local PHP deployments.
// You can create a copy in production that sets the correct backend.
window.API_BASE = window.API_BASE || null;
