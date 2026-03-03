[← Previous Part](09-database-engineering.md) | [Full Checklist](../checklist.md) | [Next Part →](11-testing-quality-assurance.md)

# Part X — Frontend Engineering

**17 sections · 153 checks**

- [13. Frontend Security & Quality](#13-frontend-security-quality)
- [43. Internationalization & Encoding Safety](#43-internationalization-encoding-safety)
- [60. Accessibility (A11y) Baseline](#60-accessibility-a11y-baseline)
- [61. Inertia.js / SPA-Specific Security](#61-inertiajs-spa-specific-security)
- [96. TypeScript & Frontend Type Safety](#96-typescript-frontend-type-safety)
- [97. Vue Component Patterns](#97-vue-component-patterns)
- [98. Tailwind CSS Hygiene](#98-tailwind-css-hygiene)
- [179. Bundle Size & Tree Shaking](#179-bundle-size-tree-shaking)
- [180. Core Web Vitals Optimization](#180-core-web-vitals-optimization)
- [181. SPA State Management Patterns](#181-spa-state-management-patterns)
- [182. Form UX & Validation Patterns](#182-form-ux-validation-patterns)
- [183. Error Boundaries & Fallback UI](#183-error-boundaries-fallback-ui)
- [184. Progressive Enhancement](#184-progressive-enhancement)
- [185. Keyboard Navigation & Focus Management](#185-keyboard-navigation-focus-management)
- [186. Frontend Error Tracking](#186-frontend-error-tracking)
- [187. Image Optimization & Lazy Loading](#187-image-optimization-lazy-loading)
- [188. Dark Mode Implementation](#188-dark-mode-implementation)

---

## 13. Frontend Security & Quality

### Form Security (Sensitive Applications)

- [ ] **Disable browser autofill on sensitive forms** — `autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"` on all inputs.
- [ ] **Never use `autocomplete="email"`, `"password"`, `"name"`** — On financial/accounting platforms, autofill is a data leak vector.

### XSS in Frontend Frameworks

- [ ] **Never use `v-html` with user data (Vue)** — Sanitise with DOMPurify first.
- [ ] **Never use `dangerouslySetInnerHTML` with user data (React)**.
- [ ] **Validate URL schemes** — When rendering user-provided links, reject `javascript:` protocol.

### Money Display

- [ ] **Format with dedicated formatter, not string templates** — Use `Intl.NumberFormat` or a composable.
- [ ] **No arithmetic with JS native numbers on money** — Use `decimal.js-light`, `big.js`, or similar.

### Cookie & CSP

- [ ] **SameSite=Strict for high-security apps** — Prevents CSRF via cross-site navigation. Use `Lax` only if cross-site login flows are needed.
- [ ] **Nonce-based CSP** — Dynamic nonce per request for inline scripts.
- [ ] **`HttpOnly` on session cookies** — Prevents JavaScript access to the session cookie.
- [ ] **`Secure` flag on all cookies in production** — Cookies sent only over HTTPS.

---


## 43. Internationalization & Encoding Safety

### Character Encoding

- [ ] **Use UTF-8 everywhere** — Database, PHP, HTML, JSON. No mixed encodings.
- [ ] **Database charset is `utf8mb4`** — Not `utf8` (which can't store emoji or some Unicode characters).
- [ ] **Use `mb_*` functions for string operations** — `mb_strlen()`, `mb_substr()`, `mb_strtolower()`. Not `strlen()`, `substr()`, `strtolower()`.

```php
// DANGEROUS — byte-level operations, breaks on multibyte
strlen('café');     // Returns 5 (bytes), not 4 (characters)
substr('café', 0, 4); // Truncates mid-character

// SAFE — character-level operations
mb_strlen('café');     // Returns 4
mb_substr('café', 0, 4); // Returns 'café'
```

### HTML Encoding

- [ ] **`htmlspecialchars()` with `ENT_QUOTES | ENT_SUBSTITUTE`** — Blade's `{{ }}` does this automatically.
- [ ] **Specify charset in `htmlspecialchars()`** — `htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')`.
- [ ] **JSON encoding with `JSON_UNESCAPED_UNICODE`** — When returning JSON containing non-ASCII characters, this keeps them readable instead of `\uXXXX` escaping.

### SQL & Unicode

- [ ] **`utf8mb4_unicode_ci` collation** — For case-insensitive, accent-insensitive comparisons.
- [ ] **Beware of Unicode normalization** — `é` (single codepoint) and `é` (e + combining accent) are different bytes but same character. Use `Normalizer::normalize()` for comparisons if relevant.

### Translation / Localization

- [ ] **Use Laravel's `__()` helper for user-facing strings** — Not hardcoded English.
- [ ] **Never concatenate translated strings** — Use placeholders: `__('Welcome, :name', ['name' => $user->name])`.
- [ ] **Validate locale input** — Don't let users set arbitrary locales: `in_array($locale, config('app.available_locales'))`.

---


## 60. Accessibility (A11y) Baseline

### Semantic HTML

- [ ] **Use proper heading hierarchy** — `h1` → `h2` → `h3`. No skipping levels.
- [ ] **Use `<button>` for actions, `<a>` for navigation** — Not `<div @click>`.
- [ ] **Form inputs have `<label>` elements** — `<label for="email">`.
- [ ] **Tables use `<th>` for headers with `scope="col"` or `scope="row"`**.

### ARIA & Keyboard

- [ ] **Interactive elements are keyboard-accessible** — Tab navigation, Enter/Space to activate.
- [ ] **`aria-label` on icon-only buttons** — `<button aria-label="Close">X</button>`.
- [ ] **`role` attributes on custom widgets** — Modals, dropdowns, tabs.
- [ ] **Focus management** — After modal open/close, focus moves to the correct element.
- [ ] **Visible focus indicators** — Don't remove `outline` without providing an alternative.

### Visual

- [ ] **Color contrast ratio minimum 4.5:1** — For normal text. 3:1 for large text.
- [ ] **Don't convey information by color alone** — Use icons, text, or patterns alongside color.
- [ ] **Responsive text** — Support browser zoom to 200% without horizontal scroll.
- [ ] **Reduced motion support** — `@media (prefers-reduced-motion: reduce)` disables animations.

---


## 61. Inertia.js / SPA-Specific Security

### Prop Exposure

- [ ] **Only send necessary data as props** — Don't send full model objects. Use Resources/DTOs.
- [ ] **Never send passwords, tokens, or secrets as props** — Even if they're on the `$hidden` array, a `toArray()` override could expose them.
- [ ] **Filter shared props** — `HandleInertiaRequests::share()` runs on every request. Don't overshare.

```php
// DANGEROUS — sharing too much
public function share(Request $request): array
{
    return [
        'auth' => [
            'user' => $request->user(), // Full user model with all attributes
        ],
    ];
}

// SAFE — selective sharing
public function share(Request $request): array
{
    return [
        'auth' => [
            'user' => $request->user() ? [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'roles' => $request->user()->getRoleNames(),
            ] : null,
        ],
    ];
}
```

### Deferred & Lazy Props

- [ ] **Deferred props load after initial page render** — Add skeleton/loading states for deferred data.
- [ ] **Optional props not sent unless requested** — Use `Inertia::optional()` for data only needed on specific interactions.
- [ ] **Don't defer auth/permission data** — Authorization data must be available immediately for UI gating.

### Navigation & History

- [ ] **`preserveScroll` on form submissions** — Prevent scroll jump to top.
- [ ] **`preserveState` for filter/search interactions** — Keep component state during partial reloads.
- [ ] **`replace: true` for redirects within flows** — Prevent back-button confusion.
- [ ] **Handle 419 (CSRF token mismatch) gracefully** — Show a "session expired, please refresh" message.

### Server-Side Rendering (SSR)

- [ ] **No `window`, `document`, `localStorage` during SSR** — Guard with `typeof window !== 'undefined'` or `onMounted()`.
- [ ] **SSR response doesn't contain user-specific data in HTML source** — Initial HTML is potentially cached. Use deferred props for user-specific data.

---


## 96. TypeScript & Frontend Type Safety

- [ ] **Strict TypeScript** — `"strict": true` in `tsconfig.json`.
- [ ] **No `any` type** — Use `unknown` and narrow. `any` bypasses all type checking.
- [ ] **Define interfaces for all API responses** — Don't use inline types.
- [ ] **Type Inertia page props** — `defineProps<{ orders: PaginatedResponse<Order> }>()`.
- [ ] **Enum mirrors for backend enums** — Every PHP enum used in the frontend has a TS equivalent.
- [ ] **No `as` type assertions** — Use type guards (`if ('status' in obj)`) instead.
- [ ] **Null checks before property access** — `user?.name` not `user.name` when nullable.
- [ ] **Function return types explicit** — Even for composables and utility functions.

---


## 97. Vue Component Patterns

- [ ] **`<script setup lang="ts">`** — Composition API with TypeScript.
- [ ] **Props are typed and documented** — `defineProps<{ title: string; count?: number }>()`.
- [ ] **Emits are typed** — `defineEmits<{ (e: 'update', value: string): void }>()`.
- [ ] **Composables for reusable logic** — `useFormatAmount()`, `useAuth()`, `useToast()`.
- [ ] **No business logic in templates** — Extract to `computed` or composables.
- [ ] **`v-if` before `v-for`** — Never on the same element. Wrap in `<template v-if>`.
- [ ] **Key all `v-for` loops** — `:key="item.id"`, not `:key="index"`.
- [ ] **Cleanup side effects in `onUnmounted()`** — Event listeners, intervals, subscriptions.
- [ ] **No direct DOM manipulation** — Use refs and reactive data. No `document.querySelector()`.

---


## 98. Tailwind CSS Hygiene

- [ ] **Use design tokens, not raw values** — `text-primary` not `text-[#1a2b3c]`.
- [ ] **Consistent spacing scale** — Don't mix `p-3` and `p-[13px]`.
- [ ] **Responsive design mobile-first** — `text-sm md:text-base lg:text-lg`.
- [ ] **Dark mode support** — Use `dark:` variants. Don't hardcode light colors.
- [ ] **No `!important` via `!` prefix** — Fix specificity instead.
- [ ] **Extract repeated patterns into components** — Not utility class strings passed as props.
- [ ] **Purge unused CSS in production** — Tailwind 4 does this automatically. Verify build output size.
- [ ] **Use `cn()` utility for conditional classes** — `cn('base', condition && 'active')`.

---


## 179. Bundle Size & Tree Shaking

- [ ] **Analyze bundle size** — `npx vite-bundle-visualizer` or `rollup-plugin-visualizer`.
- [ ] **Tree shaking works only with ES modules** — `import { specific } from 'library'`, not `require('library')`.
- [ ] **Avoid barrel file re-exports** — `import { Button } from '@/components'` may pull in everything. Import directly.

```typescript
// BAD — may include entire component library
import { Button } from '@/components';

// GOOD — tree-shakeable
import Button from '@/components/ui/button/Button.vue';
```

- [ ] **Dynamic imports for route-level code splitting** — Inertia page resolution already does this.
- [ ] **Lazy load heavy libraries** — Charts, date pickers, rich text editors: `const Chart = defineAsyncComponent(() => import('chart.js'))`.
- [ ] **Monitor bundle size in CI** — Fail build if bundle exceeds a threshold.
- [ ] **Remove unused dependencies** — `npx depcheck` identifies packages imported in `package.json` but not used.
- [ ] **CSS purging** — Tailwind 4 purges unused CSS automatically. Verify production CSS size.

---


## 180. Core Web Vitals Optimization

- [ ] **LCP (Largest Contentful Paint) < 2.5s** — Preload critical images, inline critical CSS, server-side render above-the-fold.
- [ ] **FID / INP (Interaction to Next Paint) < 200ms** — Avoid blocking the main thread. Defer non-critical JavaScript.
- [ ] **CLS (Cumulative Layout Shift) < 0.1** — Set explicit dimensions on images/iframes. No layout shifts from lazy-loaded content.
- [ ] **Preload critical assets** — `<link rel="preload" as="font" href="...">` for web fonts.
- [ ] **Compress responses** — Gzip/Brotli for HTML, CSS, JS. Nginx: `gzip on; gzip_types text/css application/javascript;`.
- [ ] **Cache static assets aggressively** — Vite adds content hashes to filenames. Set `Cache-Control: max-age=31536000, immutable`.
- [ ] **Measure in the field** — Lab tests (Lighthouse) differ from field data (CrUX). Use both.
- [ ] **Font display strategy** — `font-display: swap` prevents invisible text during font load.
- [ ] **Avoid render-blocking resources** — Defer non-critical CSS and JS. Use `async` or `defer` on script tags.

---


## 181. SPA State Management Patterns

- [ ] **Server is the source of truth** — Inertia page props are the canonical state. Don't duplicate in client stores.
- [ ] **Use `useForm()` for form state** — Handles submission, errors, processing state, and dirty tracking.

```typescript
const form = useForm({
    amount: '',
    account_id: '',
    description: '',
});

form.post(route('journal-entries.store'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
});
```

- [ ] **Composables for shared reactive state** — `useAuth()`, `useToast()`, `useSettings()`.
- [ ] **Don't use Pinia/Vuex with Inertia** — Inertia replaces the need for a client-side store. Use shared props and composables.
- [ ] **Optimistic updates with rollback** — Update the UI immediately, revert if the server rejects.
- [ ] **Debounce search inputs** — Don't fire a request on every keystroke. Use `watchDebounced` or manual `setTimeout`.
- [ ] **Clear state on navigation** — `router.on('navigate', () => { resetState() })`.
- [ ] **URL is state** — Use query parameters for filters, pagination, sorting. `router.visit(url, { data: { page: 2 } })`.

---


## 182. Form UX & Validation Patterns

- [ ] **Server-side validation is the authority** — Client-side validation is for UX, not security.
- [ ] **Display server errors per field** — Inertia provides `$page.props.errors` mapped to field names.

```vue
<template>
  <Input v-model="form.amount" :error="form.errors.amount" />
  <span v-if="form.errors.amount" class="text-sm text-red-600">{{ form.errors.amount }}</span>
</template>
```

- [ ] **Disable submit button during processing** — `form.processing` is `true` during submission.
- [ ] **Preserve scroll position on error** — `preserveScroll: true` in form options.
- [ ] **Clear errors on input change** — `form.clearErrors('amount')` when the user modifies the field.
- [ ] **Confirm destructive actions** — Delete, void, reverse: show a confirmation dialog before submitting.
- [ ] **Success feedback** — Toast notification or banner after successful submission.
- [ ] **Disable browser autofill on accounting forms** — `autocomplete="off"` on all inputs (see CLAUDE.md form security rule).
- [ ] **Tab order is logical** — `tabindex` follows the visual flow. Date → Amount → Account → Description → Submit.

---


## 183. Error Boundaries & Fallback UI

- [ ] **Vue `onErrorCaptured()` for component-level error boundaries** — Catch rendering errors without crashing the whole page.

```typescript
onErrorCaptured((error, instance, info) => {
    console.error('Component error:', error, info);
    showFallback.value = true;
    return false; // Prevent propagation
});
```

- [ ] **Fallback UI for failed deferred props** — If a deferred data load fails, show an error state, not a spinner forever.
- [ ] **Global error handler** — `app.config.errorHandler` catches unhandled errors across all components.
- [ ] **Retry buttons** — When a data fetch fails, show "Something went wrong. [Retry]" not a blank screen.
- [ ] **Graceful degradation for non-critical features** — If the notification count API fails, hide the badge; don't crash the navbar.
- [ ] **Error tracking integration** — Send frontend errors to Sentry, Bugsnag, or a custom endpoint.
- [ ] **No unhandled promise rejections** — Every `async` call has a `.catch()` or is inside a `try/catch`.

---


## 184. Progressive Enhancement

- [ ] **Core functionality works without JavaScript** — For SSR/Inertia apps, ensure the initial render is meaningful.
- [ ] **No-JS fallback for critical flows** — Login, password reset should work even if JS fails to load.
- [ ] **Loading states for deferred content** — Skeleton screens, not blank areas.

```vue
<Deferred>
  <template #fallback>
    <div class="animate-pulse h-8 bg-gray-200 rounded w-full" />
  </template>
  <ReportTable :data="reportData" />
</Deferred>
```

- [ ] **Semantic HTML first** — Use `<button>`, `<a>`, `<form>`, `<table>`, `<nav>`. Not `<div @click>`.
- [ ] **Links are links, buttons are buttons** — Navigation = `<a>`. Actions = `<button>`. Never the reverse.
- [ ] **Print stylesheets** — Financial reports should be printable. `@media print { ... }`.
- [ ] **Reduced motion** — `@media (prefers-reduced-motion: reduce) { animation: none; }`.

---


## 185. Keyboard Navigation & Focus Management

- [ ] **All interactive elements are focusable** — Buttons, links, inputs. Not `<div>` or `<span>` with click handlers.
- [ ] **Visible focus indicators** — `:focus-visible` ring on all interactive elements. Don't remove outlines.

```css
:focus-visible {
  outline: 2px solid var(--tally-accent);
  outline-offset: 2px;
}
```

- [ ] **Tab order follows visual order** — Don't use `tabindex > 0`. Use `tabindex="0"` or rely on DOM order.
- [ ] **Escape closes modals and popovers** — Consistent keyboard dismissal.
- [ ] **Arrow keys navigate within composite widgets** — Dropdown menus, date pickers, tab groups.
- [ ] **Skip navigation link** — First focusable element: "Skip to main content" link.
- [ ] **Focus trapping in modals** — Tab cycles within the modal, not behind it.
- [ ] **Announce dynamic changes** — `aria-live="polite"` for status messages, toast notifications.
- [ ] **Keyboard shortcuts documented** — If the app has shortcuts (Ctrl+S to save), document them in a help modal.

---


## 186. Frontend Error Tracking

- [ ] **Capture unhandled exceptions** — `window.addEventListener('error', handler)` and `window.addEventListener('unhandledrejection', handler)`.
- [ ] **Source maps in production (private)** — Upload source maps to Sentry/Bugsnag for readable stack traces. Don't serve them publicly.
- [ ] **Context with errors** — Include: current route, user ID (anonymized), browser/OS, page props.
- [ ] **Breadcrumbs** — Track recent user actions (clicks, navigation, API calls) leading up to the error.
- [ ] **Rate limit error reporting** — Don't send 10,000 identical error reports per minute. Deduplicate.
- [ ] **Distinguish errors by severity** — Network timeout vs. TypeError vs. ChunkLoadError require different responses.
- [ ] **`ChunkLoadError` handling** — When a deployment changes chunk hashes, old pages fail. Auto-reload on `ChunkLoadError`.

```typescript
router.on('exception', (event) => {
    if (event.detail.error?.name === 'ChunkLoadError') {
        window.location.reload();
    }
});
```

---


## 187. Image Optimization & Lazy Loading

- [ ] **Serve modern formats** — WebP or AVIF instead of PNG/JPEG. 30-50% smaller.
- [ ] **Responsive images** — `srcset` and `sizes` attributes for different viewport widths.

```html
<img
  src="/images/report-800.webp"
  srcset="/images/report-400.webp 400w, /images/report-800.webp 800w, /images/report-1200.webp 1200w"
  sizes="(max-width: 600px) 400px, (max-width: 1024px) 800px, 1200px"
  alt="Financial report"
  loading="lazy"
  decoding="async"
  width="800"
  height="600"
>
```

- [ ] **`loading="lazy"` for below-the-fold images** — Native browser lazy loading.
- [ ] **`decoding="async"`** — Don't block rendering while decoding images.
- [ ] **Explicit `width` and `height`** — Prevents layout shift (CLS).
- [ ] **SVG for icons and logos** — Scalable, small, no quality loss.
- [ ] **No images in CSS `background-image` for content images** — Use `<img>` for semantic images. CSS backgrounds for decoration.
- [ ] **Image CDN for dynamic resizing** — Cloudflare Images, Imgix, or similar. Resize on the edge, not the server.

---


## 188. Dark Mode Implementation

- [ ] **CSS custom properties for theme colors** — Switch values, not classes.

```css
:root {
  --bg-primary: #ffffff;
  --text-primary: #1a1a2e;
}

.dark {
  --bg-primary: #1a1a2e;
  --text-primary: #e0e0e0;
}
```

- [ ] **Respect `prefers-color-scheme`** — Default to the user's OS preference.
- [ ] **User override persisted** — If the user chooses light/dark, save it (localStorage or database).
- [ ] **Tailwind `dark:` variant** — `bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100`.
- [ ] **Test both modes** — Every page, every component, every state. Not just the happy path.
- [ ] **Sufficient contrast in both modes** — WCAG AA: 4.5:1 for normal text, 3:1 for large text.
- [ ] **No flash on load** — Apply the theme class before rendering. Use a `<script>` in `<head>` to set `.dark` synchronously.
- [ ] **Charts and graphs adapt** — Grid lines, labels, and legends must be readable in both modes.
- [ ] **Print always uses light mode** — `@media print { .dark { /* reset to light */ } }`.

---


---

[← Previous Part](09-database-engineering.md) | [Full Checklist](../checklist.md) | [Next Part →](11-testing-quality-assurance.md)
