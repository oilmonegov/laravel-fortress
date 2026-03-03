# Contributing to The Laravel Fortress

Thank you for your interest in improving The Laravel Fortress. Every contribution strengthens the standard for the entire Laravel community.

## Quality Bar

The bar for inclusion is simple:

> **"Would this check have prevented a real bug, security vulnerability, or production incident?"**

If yes, it belongs here. If it is a matter of personal preference or coding style, it does not.

## Types of Contributions

| Type | Description |
|------|-------------|
| **New check** | A check that catches a real class of bugs or vulnerabilities |
| **Correction** | Fix an error, outdated information, or misleading advice |
| **Code example** | Add or improve a code example for an existing check |
| **Documentation** | Improve clarity, fix typos, add cross-references |
| **AI rule system** | Improve the AI skill files, editor rules, or fortress-rules.yml |

## How to Contribute

### 1. Fork & Branch

```bash
git clone https://github.com/YOUR_USERNAME/laravel-fortress.git
cd laravel-fortress
git checkout -b add-check-description
```

### 2. Make Your Changes

- **New checks** go in the correct `parts/XX-name.md` file
- **Update `checklist.md`** to include the same check in the corresponding section
- **Follow the check format**: `- [ ] **Bold title** — Description of what to verify and why.`
- **Include code examples** where they help illustrate the correct and incorrect patterns

### 3. Check Format

Every check must follow this format:

```markdown
- [ ] **Check title** — Description that explains what to verify, why it matters, and how to fix violations.
```

Code examples use fenced blocks with language identifiers:

````markdown
```php
// SAFE — correct pattern
User::where('email', $email)->first();

// DANGEROUS — never do this
DB::select("SELECT * FROM users WHERE email = '$email'");
```
````

### 4. Test Code Examples

If your contribution includes code examples, verify they are syntactically correct. PHP examples should parse without errors. SQL examples should be valid syntax.

### 5. Submit a Pull Request

- Use a clear, descriptive title
- Fill out the PR template
- Reference any related issues
- Explain the real-world scenario that motivates the check

## Part File Targeting

Each check belongs to exactly one Part. When adding a new check, place it in the most specific Part:

| Part | File | Focus |
|------|------|-------|
| I | `parts/01-application-security.md` | OWASP, XSS, CSRF, SSRF, CSP, headers |
| II | `parts/02-cryptography-data-protection.md` | Encryption, hashing, key management, PII |
| III | `parts/03-authentication-authorization.md` | Auth, RBAC, policies, rate limiting |
| IV | `parts/04-data-integrity-concurrency.md` | Transactions, locking, idempotency, sagas |
| V | `parts/05-financial-monetary-correctness.md` | Money handling, ledgers, reconciliation |
| VI | `parts/06-php-language-type-safety.md` | PHP 8.x features, strict typing, enums |
| VII | `parts/07-clean-code-software-design.md` | SOLID, patterns, value objects, CQRS |
| VIII | `parts/08-laravel-framework-mastery.md` | Anti-patterns, validation, Eloquent, caching |
| IX | `parts/09-database-engineering.md` | Migrations, indexes, JSON columns, replicas |
| X | `parts/10-frontend-engineering.md` | Vue, TypeScript, Tailwind, accessibility |
| XI | `parts/11-testing-quality-assurance.md` | Pest/PHPUnit, mocks, coverage, mutation |
| XII | `parts/12-apis-queues-integration.md` | Jobs, webhooks, HTTP clients, Redis |
| XIII | `parts/13-logging-monitoring-audit.md` | Logging, audit trails, package audits |
| XIV | `parts/14-infrastructure-operations.md` | Config, deployment, CI/CD, Docker |

## Code of Conduct

This project follows the [Contributor Covenant v2.1](https://www.contributor-covenant.org/version/2/1/code_of_conduct/). By participating, you agree to uphold this standard. Report issues via [GitHub Issues](https://github.com/oilmonegov/laravel-fortress/issues).

## License

By contributing, you agree that your contributions will be licensed under the [MIT License](LICENSE).
