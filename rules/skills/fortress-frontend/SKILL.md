---
description: "Laravel Fortress Part 10 — Frontend Engineering. 17 sections, 153 checks covering Vue, TypeScript, Tailwind, accessibility, Inertia, bundle optimization, dark mode."
---

# Fortress: Frontend Engineering

> Part X of The Laravel Fortress — 17 sections · 153 checks
> https://github.com/oilmonegov/laravel-fortress/blob/main/parts/10-frontend-engineering.md

When writing or reviewing code, enforce these rules.
Check `.fortress.yml` in the project root for overrides.

## Project Awareness

Before enforcing rules, read `composer.json` and `package.json` to detect the project's PHP version,
Laravel version, and installed packages. Skip rules whose conditions don't match the detected stack.

## Rules

### Form Security (Sensitive Applications)

[F-P10-001] **WARNING** — Disable browser autofill on sensitive forms
[F-P10-002] **WARNING** — Never use `autocomplete="email"`, `"password"`, `"name"`

### XSS in Frontend Frameworks

[F-P10-003] **WARNING** — Never use `v-html` with user data (Vue)
[F-P10-004] **WARNING** — Never use `dangerouslySetInnerHTML` with user data (React)
[F-P10-005] **WARNING** — Validate URL schemes

### Money Display

[F-P10-006] **WARNING** — Format with dedicated formatter, not string templates
[F-P10-007] **WARNING** — No arithmetic with JS native numbers on money

### Cookie & CSP

[F-P10-008] **WARNING** — SameSite=Strict for high-security apps
[F-P10-009] **WARNING** — Nonce-based CSP
[F-P10-010] **WARNING** — `HttpOnly` on session cookies
[F-P10-011] **WARNING** — `Secure` flag on all cookies in production

### Character Encoding

[F-P10-012] **WARNING** — Use UTF-8 everywhere
[F-P10-013] **WARNING** — Database charset is `utf8mb4`
[F-P10-014] **WARNING** — Use `mb_*` functions for string operations

### HTML Encoding

[F-P10-015] **WARNING** — `htmlspecialchars()` with `ENT_QUOTES | ENT_SUBSTITUTE`
[F-P10-016] **WARNING** — Specify charset in `htmlspecialchars()`
[F-P10-017] **WARNING** — JSON encoding with `JSON_UNESCAPED_UNICODE`

### SQL & Unicode

[F-P10-018] **WARNING** — `utf8mb4_unicode_ci` collation
[F-P10-019] **WARNING** — Beware of Unicode normalization

### Translation / Localization

[F-P10-020] **WARNING** — Use Laravel's `__()` helper for user-facing strings
[F-P10-021] **WARNING** — Never concatenate translated strings
[F-P10-022] **WARNING** — Validate locale input

### Semantic HTML

[F-P10-023] **WARNING** — Use proper heading hierarchy
[F-P10-024] **WARNING** — Use `<button>` for actions, `<a>` for navigation
[F-P10-025] **WARNING** — Form inputs have `<label>` elements
[F-P10-026] **WARNING** — Tables use `<th>` for headers with `scope="col"` or `scope="row"`

### ARIA & Keyboard

[F-P10-027] **WARNING** — Interactive elements are keyboard-accessible
[F-P10-028] **WARNING** — `aria-label` on icon-only buttons
[F-P10-029] **WARNING** — `role` attributes on custom widgets
[F-P10-030] **WARNING** — Focus management
[F-P10-031] **WARNING** — Visible focus indicators

### Visual

[F-P10-032] **WARNING** — Color contrast ratio minimum 4.5:1
[F-P10-033] **WARNING** — Don't convey information by color alone
[F-P10-034] **WARNING** — Responsive text
[F-P10-035] **WARNING** — Reduced motion support

### Prop Exposure

[F-P10-036] **WARNING** — Only send necessary data as props
[F-P10-037] **WARNING** — Never send passwords, tokens, or secrets as props
[F-P10-038] **WARNING** — Filter shared props

### Deferred & Lazy Props

[F-P10-039] **WARNING** — Deferred props load after initial page render
[F-P10-040] **WARNING** — Optional props not sent unless requested
[F-P10-041] **WARNING** — Don't defer auth/permission data

### Navigation & History

[F-P10-042] **WARNING** — `preserveScroll` on form submissions
[F-P10-043] **WARNING** — `preserveState` for filter/search interactions
[F-P10-044] **WARNING** — `replace: true` for redirects within flows
[F-P10-045] **WARNING** — Handle 419 (CSRF token mismatch) gracefully

### Server-Side Rendering (SSR)

[F-P10-046] **WARNING** — No `window`, `document`, `localStorage` during SSR
[F-P10-047] **WARNING** — SSR response doesn't contain user-specific data in HTML source

### TypeScript & Frontend Type Safety

[F-P10-048] **WARNING** — Strict TypeScript
[F-P10-049] **WARNING** — No `any` type
[F-P10-050] **WARNING** — Define interfaces for all API responses
[F-P10-051] **WARNING** — Type Inertia page props
[F-P10-052] **WARNING** — Enum mirrors for backend enums
[F-P10-053] **WARNING** — No `as` type assertions
[F-P10-054] **WARNING** — Null checks before property access
[F-P10-055] **WARNING** — Function return types explicit

### Vue Component Patterns

[F-P10-056] **WARNING** — `<script setup lang="ts">`
[F-P10-057] **WARNING** — Props are typed and documented
[F-P10-058] **WARNING** — Emits are typed
[F-P10-059] **WARNING** — Composables for reusable logic
[F-P10-060] **WARNING** — No business logic in templates
[F-P10-061] **WARNING** — `v-if` before `v-for`
[F-P10-062] **WARNING** — Key all `v-for` loops
[F-P10-063] **WARNING** — Cleanup side effects in `onUnmounted()`
[F-P10-064] **WARNING** — No direct DOM manipulation

### Tailwind CSS Hygiene

[F-P10-065] **WARNING** — Use design tokens, not raw values
[F-P10-066] **WARNING** — Consistent spacing scale
[F-P10-067] **WARNING** — Responsive design mobile-first
[F-P10-068] **WARNING** — Dark mode support
[F-P10-069] **WARNING** — No `!important` via `!` prefix
[F-P10-070] **WARNING** — Extract repeated patterns into components
[F-P10-071] **WARNING** — Purge unused CSS in production
[F-P10-072] **WARNING** — Use `cn()` utility for conditional classes

### Bundle Size & Tree Shaking

[F-P10-073] **WARNING** — Analyze bundle size
[F-P10-074] **WARNING** — Tree shaking works only with ES modules
[F-P10-075] **WARNING** — Avoid barrel file re-exports
[F-P10-076] **WARNING** — Dynamic imports for route-level code splitting
[F-P10-077] **WARNING** — Lazy load heavy libraries
[F-P10-078] **WARNING** — Monitor bundle size in CI
[F-P10-079] **WARNING** — Remove unused dependencies
[F-P10-080] **WARNING** — CSS purging

### Core Web Vitals Optimization

[F-P10-081] **WARNING** — LCP (Largest Contentful Paint) < 2.5s
[F-P10-082] **WARNING** — FID / INP (Interaction to Next Paint) < 200ms
[F-P10-083] **WARNING** — CLS (Cumulative Layout Shift) < 0.1
[F-P10-084] **WARNING** — Preload critical assets
[F-P10-085] **WARNING** — Compress responses
[F-P10-086] **WARNING** — Cache static assets aggressively
[F-P10-087] **WARNING** — Measure in the field
[F-P10-088] **WARNING** — Font display strategy
[F-P10-089] **WARNING** — Avoid render-blocking resources

### SPA State Management Patterns

[F-P10-090] **WARNING** — Server is the source of truth
[F-P10-091] **WARNING** — Use `useForm()` for form state
[F-P10-092] **WARNING** — Composables for shared reactive state
[F-P10-093] **WARNING** — Don't use Pinia/Vuex with Inertia
[F-P10-094] **WARNING** — Optimistic updates with rollback
[F-P10-095] **WARNING** — Debounce search inputs
[F-P10-096] **WARNING** — Clear state on navigation
[F-P10-097] **WARNING** — URL is state

### Form UX & Validation Patterns

[F-P10-098] **WARNING** — Server-side validation is the authority
[F-P10-099] **WARNING** — Display server errors per field
[F-P10-100] **WARNING** — Disable submit button during processing
[F-P10-101] **WARNING** — Preserve scroll position on error
[F-P10-102] **WARNING** — Clear errors on input change
[F-P10-103] **WARNING** — Confirm destructive actions
[F-P10-104] **WARNING** — Success feedback
[F-P10-105] **WARNING** — Disable browser autofill on accounting forms
[F-P10-106] **WARNING** — Tab order is logical

### Error Boundaries & Fallback UI

[F-P10-107] **WARNING** — Vue `onErrorCaptured()` for component-level error boundaries
[F-P10-108] **WARNING** — Fallback UI for failed deferred props
[F-P10-109] **WARNING** — Global error handler
[F-P10-110] **WARNING** — Retry buttons
[F-P10-111] **WARNING** — Graceful degradation for non-critical features
[F-P10-112] **WARNING** — Error tracking integration
[F-P10-113] **WARNING** — No unhandled promise rejections

### Progressive Enhancement

[F-P10-114] **WARNING** — Core functionality works without JavaScript
[F-P10-115] **WARNING** — No-JS fallback for critical flows
[F-P10-116] **WARNING** — Loading states for deferred content
[F-P10-117] **WARNING** — Semantic HTML first
[F-P10-118] **WARNING** — Links are links, buttons are buttons
[F-P10-119] **WARNING** — Print stylesheets
[F-P10-120] **WARNING** — Reduced motion

### Keyboard Navigation & Focus Management

[F-P10-121] **WARNING** — All interactive elements are focusable
[F-P10-122] **WARNING** — Visible focus indicators
[F-P10-123] **WARNING** — Tab order follows visual order
[F-P10-124] **WARNING** — Escape closes modals and popovers
[F-P10-125] **WARNING** — Arrow keys navigate within composite widgets
[F-P10-126] **WARNING** — Skip navigation link
[F-P10-127] **WARNING** — Focus trapping in modals
[F-P10-128] **WARNING** — Announce dynamic changes
[F-P10-129] **WARNING** — Keyboard shortcuts documented

### Frontend Error Tracking

[F-P10-130] **WARNING** — Capture unhandled exceptions
[F-P10-131] **WARNING** — Source maps in production (private)
[F-P10-132] **WARNING** — Context with errors
[F-P10-133] **WARNING** — Breadcrumbs
[F-P10-134] **WARNING** — Rate limit error reporting
[F-P10-135] **WARNING** — Distinguish errors by severity
[F-P10-136] **WARNING** — `ChunkLoadError` handling

### Image Optimization & Lazy Loading

[F-P10-137] **WARNING** — Serve modern formats
[F-P10-138] **WARNING** — Responsive images
[F-P10-139] **WARNING** — `loading="lazy"` for below-the-fold images
[F-P10-140] **WARNING** — `decoding="async"`
[F-P10-141] **WARNING** — Explicit `width` and `height`
[F-P10-142] **WARNING** — SVG for icons and logos
[F-P10-143] **WARNING** — No images in CSS `background-image` for content images
[F-P10-144] **WARNING** — Image CDN for dynamic resizing

### Dark Mode Implementation

[F-P10-145] **WARNING** — CSS custom properties for theme colors
[F-P10-146] **WARNING** — Respect `prefers-color-scheme`
[F-P10-147] **WARNING** — User override persisted
[F-P10-148] **WARNING** — Tailwind `dark:` variant
[F-P10-149] **WARNING** — Test both modes
[F-P10-150] **WARNING** — Sufficient contrast in both modes
[F-P10-151] **WARNING** — No flash on load
[F-P10-152] **WARNING** — Charts and graphs adapt
[F-P10-153] **WARNING** — Print always uses light mode
