# Current State Audit And Implementation Roadmap

Prepared for: Ricky / Client Stakeholders  
Project: Track Citations  
Assessment date: July 7, 2026

## Executive Summary

Track Citations already has a meaningful operational foundation. The current system is a Laravel application with Blade-based dashboards, role-based authentication, ticket/citation management, company and driver records, messaging, notifications, and a working Salesforce pull-sync process.

This is important because the project should not be positioned as a rebuild from scratch, and it should not be positioned only as a theme refresh. The larger need is to improve how the platform works for internal staff, company-side users, and drivers while formalizing the account hierarchy, company structure, sync governance, and reporting model.

The current codebase is best described as:

- Laravel + Blade application
- small amount of frontend JavaScript and Alpine
- not yet a true Vue application in practice
- partially modernized app structure
- partially legacy Salesforce sync tooling still present outside the Laravel app

The recommended path is to continue progressively within Laravel, strengthen the business model first, improve access and workflow second, and modernize UI only in support of those workflows.

## Scope Reviewed

This audit reviewed the following areas:

- Laravel and frontend application structure
- Current database and user structure
- Current roles and permissions
- Company, driver, and ticket relationships
- Salesforce integration and sync patterns
- AWS S3/file upload flow
- Existing reusable structure and simplification opportunities
- Business-impact priorities and implementation roadmap

The Salesforce Sync Inspector URL provided for discovery was also checked:

- `https://trackcitations.com//sfdc_datasync/salesforce-sync-inspector.php`

Result on July 7, 2026:

- the referenced URL was not reachable and returned `404 Not Found`
- the local project snapshot also does not contain a file named `salesforce-sync-inspector.php`

Because of that, the inspector itself could not be directly audited in this phase. The report below instead evaluates the current Salesforce sync implementation, the legacy `sfdc_datasync` area, and the security considerations that would apply to such an inspector tool.

## Current Findings

### 1. Application structure

The main application is Laravel 11 with server-rendered Blade views. Frontend tooling uses Vite and Tailwind, but the current app is not structured as a Vue SPA or even a Vue-heavy hybrid.

Current state:

- Blade templates are the primary UI layer
- Alpine is loaded in the main frontend entrypoint
- Axios is configured
- Laravel Echo scaffolding exists but is commented out
- no active `.vue` component structure was found in the app snapshot

Assessment:

- The application is better described as `Laravel + Blade + Alpine` than `Laravel + Vue`
- `Option 1: Blade + Vue Components` is still a good future direction, but it would be an incremental adoption path, not a continuation of an already established Vue architecture

### 2. Current account and user model

The current system uses a polymorphic `users` table with role-specific records attached via `roleable_id` and `roleable_type`.

Current roles observed:

- Admin
- Manager
- Attorney
- Driver

Assessment:

- This works for the current platform
- It does not yet reflect the desired future business structure of:
  - Super Admin
  - Staff Admin
  - Company Admin
  - Parent Company hierarchy
  - Multiple Trucking Companies
  - Driver self-service access

Key finding:

- `manager` is currently the closest equivalent to a company-side user, but it is not a clean long-term replacement for a true `Company Admin` model

### 3. Roles and permissions

The system uses role-based access plus policy checks and company assignment logic.

What is working:

- Admins have broad system control
- Managers are scoped to assigned companies
- Drivers have individual logins
- Attorneys can be assigned to tickets

What is missing:

- clear separation between `Super Admin` and internal `Staff Admin`
- formal `Company Admin` role
- explicit parent-company rollup permissions
- cleaner customer-facing visibility rules by role

Assessment:

- The current permission model is usable
- It should be refactored before expanding customer-facing portals
- Policy rules should be simplified around business entities rather than patched role-by-role

### 4. Company, trucking company, driver, and ticket relationships

Current state:

- Companies exist
- Drivers exist
- Tickets exist
- Managers can belong to multiple companies
- Attorneys can be assigned to tickets

What is not yet modeled:

- parent company
- child trucking company hierarchy
- direct company-tree rollups
- clean ticket ownership chain from parent company to trucking company to driver

Important structural limitation:

- tickets do not appear to be modeled around a strong direct driver ownership key in the way needed for a robust driver portal and rollup reporting

Assessment:

- The current data structure can support operations
- It is not yet ideal for the future account hierarchy or for reliable company/driver analytics

### 5. Salesforce integration

Salesforce pull sync is already a major part of the system and is one of the strongest foundations already present.

Current observed behavior:

- pulls Salesforce data into Track Citations
- pulls attachments and file-like records
- stores Salesforce IDs locally
- refreshes access tokens
- schedules sync automatically

Observed business direction match:

- the current integration is mostly aligned with the desired “Salesforce as source of truth” approach
- the system currently appears oriented toward pull, not push, which is appropriate for this phase

Limitations:

- mapping is currently implemented in code, but not yet documented as a formal object/field map for discovery
- some assumptions appear hardcoded
- long-term governance for ownership, conflict handling, and hierarchy mapping is not yet formalized

### 6. Salesforce Sync Inspector / helper review

The exact inspector file could not be accessed on July 7, 2026 because the URL returned `404`.

Even without direct access, the intended purpose described for the inspector is reasonable for discovery:

- inspect available Salesforce objects
- inspect fields
- verify connection
- evaluate mapping candidates
- review source IDs to store locally

Assessment:

- this should be treated only as an internal developer/admin tool
- it should never remain public-facing in production

Required controls if restored or recreated:

- admin authentication
- IP restriction or VPN restriction
- audit logging
- environment gating
- removal after discovery/testing

Additional concern:

- the legacy `sfdc_datasync` area in the local snapshot contains hardcoded operational credentials in plain PHP config, which indicates the sync tooling area needs cleanup and hardening before any continued use

### 7. AWS S3 and file upload flow

Current state:

- Laravel filesystem includes an `s3` disk configuration
- current uploads appear to use the local/public disk, not S3
- ticket attachments and public ticket submission uploads are stored through `Storage::disk('public')`
- Salesforce-synced files are also being stored through the public disk path pattern

Assessment:

- S3 is configured as a platform capability
- S3 is not yet the active or completed storage implementation for the main file flows reviewed

Implication:

- AWS S3 should be treated as partially prepared but not completed

### 8. Current usability and workflow state

The system already supports the basic operations needed to manage records, but the experience appears organized more around internal CRUD screens than around the day-to-day workflows of support staff, company admins, and drivers.

Main UX gaps:

- no dedicated staff support workspace for fast status lookup
- no mature company admin portal
- driver portal is present but appears basic
- no clear parent-company dashboard model
- no formal points-saved reporting experience

### 9. UI consistency review

The main UI issue is not visual inconsistency alone. The larger issue is that the current interface is not yet organized around the future operational workflows.

Relevant UI concerns:

- multiple role areas appear to mirror internal CRUD patterns
- role experiences do not yet feel intentionally separated by job-to-be-done
- attachment/document flows and record timelines can likely be simplified

Assessment:

- visual polish matters, but only after workflow structure and access rules are clarified

## What Is Already Done

- Laravel 11 backend application is in place
- authentication is implemented
- email verification is implemented
- role-based account system exists
- company records exist
- driver records exist
- attorney records exist
- ticket/citation records exist
- attachments exist
- ticket notes exist
- notifications exist
- internal messaging exists
- dashboard views exist by role
- Salesforce pull sync exists
- token refresh logic exists
- scheduled sync exists
- local storage upload flow exists

## What Is Partially Done

- company-facing access through the current `manager` role
- driver self-service access in a basic form
- Salesforce source-of-truth pattern in practice, but not yet fully governed
- S3 configuration without full S3 adoption in reviewed flows
- dashboard/reporting foundations without full customer-facing reporting

## What Is Missing

### Account hierarchy

- Super Admin role
- Staff Admin role
- formal Company Admin role
- parent company hierarchy
- multiple trucking companies under a parent account

### Access and workflow

- support-first staff workspace
- mature company admin portal
- parent company rollup access
- cleaner customer-facing status visibility
- stronger permission model for future growth

### Data model

- parent-child company relationships
- stronger driver-ticket ownership model
- reporting-friendly hierarchy structure

### Salesforce governance

- documented object mapping
- documented field mapping
- sync audit and governance rules
- approved cadence alignment
- cleanup/hardening of legacy sync utilities

### Storage and documents

- completed S3 implementation
- clarified private/public file strategy
- hardened admin/dev-only inspection tooling

### Reporting

- per-ticket points saved
- driver lifetime points saved
- company lifetime points saved
- parent company rollup points saved

## Recommended Improvements

### High priority

- refactor roles and permissions to match the business hierarchy
- add parent company and trucking company hierarchy
- strengthen driver-ticket ownership model
- define Salesforce object and field mapping formally
- harden Salesforce token/auth tooling
- secure or remove any internal inspection tooling
- complete S3 strategy for file storage

### Medium priority

- build staff support workspace
- build company admin experience
- improve driver portal
- add points-saved calculations and reporting

### Lower priority

- broader visual redesign
- deeper component library cleanup
- non-essential cosmetic standardization

## Required Changes To Support The New Account Hierarchy

- split current `admin` responsibilities into:
  - Super Admin
  - Staff Admin
- convert current company-side `manager` concept into a true Company Admin model
- add parent company support in the database
- allow one parent account to manage multiple trucking companies
- formalize driver ownership and access under the company hierarchy
- rework policy rules around company tree access and role responsibilities

## Required Changes For Staff, Company Admin, And Driver Access

### Staff Admin

- fast search across companies, drivers, and tickets
- ticket status history visibility
- Salesforce sync visibility
- support-call oriented workflow screens

### Company Admin

- company dashboard
- driver list
- open/closed case visibility
- ticket/citation history
- points saved reporting
- support for single-company and multi-company access

### Driver

- personal case history
- current status and updates
- resolved/closed cases
- points saved visibility when available
- simplified self-service document and communication flow

## Salesforce Sync Considerations

- continue with pull-only sync in this phase
- do not enable Salesforce pushback without explicit design approval
- reduce sync schedule from every 5 minutes to every 15 minutes if that is the approved business cadence
- document which local records should store Salesforce IDs
- define which Salesforce objects are canonical for:
  - company
  - parent company
  - company admin/contact
  - driver
  - ticket/citation
  - attachment/document
  - status
  - point values

## Salesforce Object And Field Mapping Recommendations

This should be formalized in discovery as a mapping matrix. Based on the current implementation, the immediate recommendation is to document the following:

- source object for companies
- source object for driver/person records
- source object for ticket/citation records
- source object for attachments/files
- source field for company hierarchy
- source field for primary contact / company admin
- source field for driver identity
- source field for attorney assignment
- source field for ticket status / disposition
- source fields for original points and reduced/final points
- source Salesforce IDs that should be stored locally to prevent duplicates

## Risks And Dependencies

### Risks

- permission complexity increases if hierarchy is added without refactoring the current role model
- customer-facing access could expose incorrect data if the current ownership logic is expanded without cleanup
- legacy sync tooling may create security exposure if left accessible
- hardcoded/legacy credential handling must be remediated
- reporting can become unreliable if points-saved logic is layered onto the current model without structural fixes

### Dependencies

- confirmed Salesforce object model
- confirmed business definition of parent company vs trucking company
- approved permission matrix by role
- final decision on S3/private file storage strategy
- clarified points-saved formula and source fields

## Practical Implementation Roadmap

### Phase 1: Discovery, mapping, and architecture

Deliverables:

- current-state architecture map
- role and permission matrix
- company hierarchy model
- driver ownership model
- Salesforce object/field mapping matrix
- S3/file storage decision
- points-saved rules

Estimated effort:

- `24-36 hours`

### Phase 2: Permissions system and account hierarchy

Deliverables:

- Super Admin and Staff Admin separation
- Company Admin model
- revised policy and authorization model
- role migration strategy

Estimated effort:

- `28-40 hours`

### Phase 3: Company / trucking company hierarchy

Deliverables:

- parent-child company schema
- migration strategy
- company tree access rules
- multi-company rollup support

Estimated effort:

- `24-36 hours`

### Phase 4: Salesforce token/authentication fix

Deliverables:

- move legacy token/auth concerns into secure Laravel-managed flow
- remove or isolate hardcoded credential handling
- validate refresh and failure handling

Estimated effort:

- `12-18 hours`

### Phase 5: Salesforce sync and admin-side implementation

Deliverables:

- documented object/field mapping
- improved sync governance
- admin-facing sync status visibility
- approved 15-minute schedule
- duplicate prevention strategy

Estimated effort:

- `32-48 hours`

### Phase 6: Salesforce Sync Inspector review or rebuild

Deliverables:

- verify whether inspector should be restored, rebuilt, or replaced
- admin-only or IP-restricted access
- safe field/object inspection capability
- removal plan after discovery

Estimated effort:

- `8-14 hours`

### Phase 7: AWS S3 completion

Deliverables:

- move target upload flows to S3 where appropriate
- confirm public/private access strategy
- validate attachment URLs and migration approach

Estimated effort:

- `14-24 hours`

### Phase 8: Staff, company admin, and driver access levels

Deliverables:

- staff support workflows
- company admin workflows
- driver portal access rules
- record visibility by role

Estimated effort:

- `24-36 hours`

### Phase 9: Dashboard and workflow updates

Deliverables:

- staff support dashboard
- company admin dashboard
- driver dashboard improvements
- company rollup screens
- improved ticket history and status views

Estimated effort:

- `36-56 hours`

### Phase 10: Points saved calculations and reporting

Deliverables:

- per-ticket points saved
- driver lifetime points saved
- company lifetime points saved
- parent company rollups
- dashboard/report display

Estimated effort:

- `16-28 hours`

### Phase 11: UI modernization in support of workflows

Deliverables:

- reusable components for dashboards, filters, tables, and case timelines
- targeted modernization of high-value screens
- optional progressive Vue component adoption where useful

Estimated effort:

- `28-48 hours`

## Estimated Hours By Requested Section

- Permissions system: `28-40 hours`
- Staff, company admin, and driver access levels: `24-36 hours`
- Company / trucking company hierarchy: `24-36 hours`
- Salesforce token/authentication fix: `12-18 hours`
- Salesforce sync/admin-side implementation: `32-48 hours`
- Salesforce Sync Inspector review: `8-14 hours`
- AWS S3 completion: `14-24 hours`
- Dashboard/workflow updates: `36-56 hours`
- Points saved calculations: `16-28 hours`

Estimated subtotal for the follow-on implementation sections above:

- `194-300 hours`

This estimate excludes:

- major visual brand redesign
- content writing
- production data cleanup outside the reviewed scope
- unplanned Salesforce schema changes on the Salesforce side

## Priority Order By Business Impact

### Highest impact first

1. Discovery and mapping confirmation
2. Permissions and account hierarchy
3. Company/trucking company hierarchy
4. Salesforce auth/security hardening
5. Salesforce sync governance
6. Staff/company/driver workflow access
7. Dashboard updates
8. Points saved reporting
9. S3 completion
10. UI modernization

## Bottom Line

Track Citations already has a strong enough backend foundation to move forward without a rebuild. The right next step is a structured enhancement program focused on hierarchy, permissions, Salesforce governance, storage hardening, and workflow design.

The most important message for the client is:

- the platform is already functional
- the project is not only a UI refresh
- the biggest missing work is in business structure, access model, sync governance, and reporting
- UI modernization should follow those decisions, not drive them

This makes the recommended path a phased Laravel-first improvement effort, with progressive frontend modernization only where it directly improves staff, company admin, and driver workflows.
