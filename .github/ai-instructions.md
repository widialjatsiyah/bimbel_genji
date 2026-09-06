# AI Rules

## Project Context

This is a CodeIgniter 3 HMVC PHP application. Preserve the existing architecture and conventions. The application uses Composer dependencies, Bootstrap/jQuery/DataTables on the frontend, MySQL through CodeIgniter Query Builder, PhpSpreadsheet for Excel imports, mPDF, and Midtrans.

## Required Workflow

1. Inspect the owning controller, model, view, nearby call site, or failing test before editing.
2. Form one local hypothesis about the behavior and identify one focused validation check.
3. Keep edits minimal and local. Do not refactor unrelated code.
4. Preserve existing public APIs, response shapes, naming, indentation, and Bootstrap version.
5. After the first substantive edit, run the narrowest available validation immediately.
6. Finish with PHP lint or another executable check for every changed code path.

## Project Tree Rules

Use this placement:

```text
application/
├── config/                 # application configuration and routes
├── controllers/            # shared controllers
├── core/                   # CodeIgniter extensions
├── libraries/              # application libraries
├── models/                 # shared models
├── modules/<module>/
│   ├── controllers/        # module controllers
│   ├── models/              # module-only models, when needed
│   └── views/               # index.php, form.php, main.js.php
└── views/                  # shared views and partials
database/                   # schema changes and SQL migrations
themes/                     # themes and frontend assets
uploads/                    # user-uploaded files
```

Place shared business/data models in `application/models/`. Place a module-specific controller in `application/modules/<module>/controllers/`. Do not create parallel root-level folders.

## PHP and Controller Style

- Use `defined('BASEPATH') or exit('No direct script access allowed');`.
- Controllers extend `AppBackend` when they are backend/application pages.
- Call `parent::__construct()`.
- Use PascalCase for class/file names and snake_case for functions and URLs.
- Existing functions use names such as `ajax_get_all`, `ajax_save`, `ajax_delete`, `upload_manual_proof`.
- Load dependencies in the constructor with `$this->load->model(...)`.
- Call `$this->handle_ajax_request()` at the start of protected AJAX endpoints.
- Return JSON consistently with `status` and `data`; use `AppModel->getData_dtAjax()` for server-side DataTables.
- Use CodeIgniter Query Builder and validation. Never concatenate untrusted input into SQL.
- Use `static_conditional_spec` for exact ID/user filters; `static_conditional` is LIKE-based in this project.
- Enforce authorization and ownership on the server, especially for student, payment, upload, and admin endpoints.
- Use tabs/spaces and surrounding formatting from the file being edited. Avoid unrelated reformatting.

## Model Style

Models use a private table property and familiar methods such as `getAll`, `getDetail`, `insert`, `update`, and `delete`. Keep return types and response structures compatible with neighboring models. For database schema changes, create a descriptive SQL file in `database/`; do not edit already-applied migrations casually.

## View and JavaScript Style

- Use `views/index.php` for the page, `views/form.php` for reusable forms, and `views/main.js.php` for module JavaScript.
- This project uses Bootstrap 4-style attributes: `data-toggle`, `data-target`, and `data-dismiss`.
- DataTables headers and JavaScript `columns` must stay in the same order and count.
- Use delegated click handlers for dynamic table buttons.
- Keep modal IDs identical between PHP markup and JavaScript.
- Escape user-controlled values before placing them into HTML attributes or dynamic markup.
- Do not leave unused gateway containers, fixed-height placeholders, or empty visual boxes in a page.
- Keep status labels and badges aligned with actual database values.

## Payment Rules

Midtrans and manual payments are different flows:

- Midtrans status is updated by the verified webhook and is authoritative.
- Manual payment uses `payment_type = 'manual'`, stores a proof file, and requires admin verification.
- Manual verification statuses are `pending`, `approved`, and `rejected`.
- Activate a package only after Midtrans `settlement`/`capture` or manual admin approval.
- Verify the logged-in user owns the transaction before reading, uploading, or changing payment data.
- Validate upload extension and size, encrypt filenames, and remove replaced files only after a successful database update.

## Naming and File Creation

- Controllers/classes: PascalCase, matching the existing project pattern, for example `My_payment` and `UserPackageModel`.
- Functions/URLs: snake_case.
- Views: `index.php`, `form.php`, `main.js.php`.
- SQL files: descriptive lowercase snake_case names.
- New files must follow the existing module tree and must not be placed in `system/` or `vendor/`.

## Validation Checklist

For every change:

- Run `php -l` on changed PHP files.
- Check editor diagnostics for changed files.
- For DataTables, verify server select fields, table headers, and JS columns together.
- For AJAX, verify URL, HTTP method, CSRF, response JSON, success branch, and error branch.
- For SQL/schema work, inspect joins, duplicate-row risk, user scoping, and null behavior.
- Report tests that could not be run because a database, web server, or external gateway is required.

## Safety

Never revert user changes. Never run destructive git commands. Do not commit or create branches unless explicitly requested. Do not expose credentials, Midtrans keys, uploaded files, or private user data in logs or responses.
