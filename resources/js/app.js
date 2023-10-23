import './bootstrap';
import './main.js';
import * as Sentry from "@sentry/browser";

import.meta.glob([
    '../images/**',
    '../fonts/**'
])

Sentry.init({
    dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
});
