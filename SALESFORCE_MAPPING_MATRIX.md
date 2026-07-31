# Salesforce Mapping Matrix

Prepared for the Track Citations Laravel implementation  
Last updated: July 23, 2026

## Purpose

This document is the current working Salesforce mapping for the Laravel application. It combines:

- the implemented Laravel sync behavior
- the client-provided mapping PDF dated July 21, 2026
- the current business rules already approved for discovery

This document is intended to show:

- what is already implemented in code
- what is confirmed by the business mapping document
- what still requires live Salesforce API-name validation

Primary code references:

- `app/Integrations/Salesforce/SalesforceService.php`
- `app/Integrations/Salesforce/SalesforceSyncService.php`
- `app/Console/Commands/SalesforceSyncData.php`
- `app/Http/Controllers/SalesForceController.php`

## Integration Rules

These rules are now documented and treated as confirmed unless the client changes scope later.

| Rule | Status | Notes |
|---|---|---|
| Salesforce is the source of truth | Confirmed | Companies, drivers, tickets/citations, statuses, points, and related sync data should originate from Salesforce |
| Sync direction is pull-only | Confirmed | Citation Tracker should not push changes back to Salesforce in the current phase |
| Sync cadence target is every 15 minutes | Confirmed | Scheduler/runtime still needs live environment validation |
| Salesforce record ID is the external unique key | Confirmed | Stored locally on synced records and used for duplicate prevention |
| Incremental sync should prefer `SystemModstamp`, otherwise `LastModifiedDate` | Confirmed by mapping direction, partially implemented | Current Laravel code uses `LastModifiedDate`; `SystemModstamp` is still a future refinement |
| Recommended sync order: companies, drivers, attorneys/reference data, tickets, attachments | Confirmed | Current code partially follows this order through contact-driven ticket sync plus separate attachment sync |

## Mapping Status Legend

| Status | Meaning |
|---|---|
| Implemented | Actively mapped in Laravel sync code today |
| Confirmed | Confirmed by the client mapping PDF/business rules |
| Validate in Salesforce | Needs object/field API verification in the live Salesforce environment |
| Pending design decision | Requires business or implementation decision before finalization |

## Object-to-System Mapping

| Salesforce Object / Concept | Citation Tracker Destination | Status | Notes |
|---|---|---|---|
| `Account` | `companies` | Implemented + Confirmed | Used for company records; parent/child hierarchy should ultimately follow `ParentId` or confirmed equivalent |
| `Contact` | ticket source, driver source, attorney source | Implemented + Confirmed | Current code syncs ticket-oriented contact data; dedicated driver materialization is still incomplete |
| Ticket/Citation custom object | `tickets` | Confirmed, not implemented as a separate source object | The client mapping expects a dedicated ticket/citation object, but the current Laravel sync still reads ticket data from `Contact` |
| Attorney custom object or `Contact` | `attorneys` / attorney users | Implemented from contact fields, final source object still to validate | Current code creates attorneys from attorney-related fields returned on contact records |
| `ContentDocumentLink` | file-to-record relationship | Implemented + Confirmed | Used to resolve which Salesforce record a file belongs to |
| `ContentDocument` | file metadata linkage | Confirmed | Referenced through `ContentVersion` relationship flow |
| `ContentVersion` | `ticket_attachments` | Implemented + Confirmed | Latest-version file download path is already supported |
| Legacy `Attachment` | `ticket_attachments` | Implemented + Confirmed | Kept for historical compatibility during transition |
| `Task` / `Note` / activity objects | notes/activity/messages | Pending design decision | Not currently mapped in Laravel sync |

## Company / Account Mapping

### Confirmed Direction

- Salesforce `Account` is the source for company records.
- Parent/child company structure should use Salesforce account hierarchy.
- Local duplicate prevention should use `companies.sf_id = Account.Id`.

### Implemented Local Mapping

| Salesforce Field | Local Field / Usage | Status | Notes |
|---|---|---|---|
| `Account.Id` | `companies.sf_id` | Implemented + Confirmed | Canonical duplicate-prevention key |
| `Account.Name` | `companies.name` | Implemented + Confirmed | Primary company name |
| `Account.Citation_Tracker_User_Email__c` | `companies.ct_email` | Implemented | Company-side Track Citations contact field |
| `Account.Citation_Tracker_User_First_Name__c` | `companies.ct_fname` | Implemented | Company-side Track Citations contact field |
| `Account.Citation_Tracker_User_Last_Name__c` | `companies.ct_lname` | Implemented | Company-side Track Citations contact field |
| `Account.DOT_Number__c` | `companies.dot` | Implemented + Confirmed | DOT field is in use |
| `Account.Contact_Email__c` | company admin email candidate | Implemented | Used first for company-admin account creation |
| `Account.Primary_Contact_Email__c` | fallback company admin email candidate | Implemented | Used when `Contact_Email__c` is empty |
| `Account.Phone` | company-admin user phone | Implemented | Used when creating/updating company-admin user |
| `Account.BillingStreet` | company-admin user address | Implemented | Profile bootstrap field |
| `Account.BillingCity` | company-admin user city | Implemented | Profile bootstrap field |
| `Account.BillingState` | company-admin user state | Implemented | Profile bootstrap field |
| `Account.BillingPostalCode` | company-admin user zip | Implemented | Profile bootstrap field |
| `Account.Export__c` | sync eligibility filter | Implemented + Confirmed | Contact query currently filters on `Account.Export__c = TRUE` |
| `Account.ParentId` | `companies.parent_company_id` | Confirmed, not implemented in sync | Local hierarchy support exists, but the Salesforce sync does not yet write parent-child hierarchy from `ParentId` |
| Account status field | active/inactive company state | Validate in Salesforce | API name and behavior still need validation |
| Account owner / staff assignment | optional internal assignment | Pending design decision | Not currently synced |

## Driver / Contact Mapping

### Confirmed Direction

- Drivers should ultimately come from Salesforce contact data.
- Company relationship should normally come from `AccountId` unless a custom relationship field is confirmed.
- Driver solo login and visibility rules still need business confirmation.

### Current Laravel Reality

The current sync does **not** fully materialize driver records from Salesforce into the local `drivers` table. Instead:

- ticket records are synced from `Contact`
- driver-identifying fields live on the synced ticket
- local driver accounts are still managed separately through the application workflow

### Driver Mapping Table

| Salesforce Field / Concept | Local Destination / Behavior | Status | Notes |
|---|---|---|---|
| `Contact.Id` | future driver Salesforce key | Confirmed | Not yet stored on a dedicated driver record by the current sync |
| `FirstName` | driver first name | Available in query, not currently written separately | Current ticket sync stores `Name` rather than splitting names |
| `LastName` | driver last name | Available in query, not currently written separately | Same note as above |
| `Name` | `tickets.name` | Implemented | Current sync uses full contact name on the ticket |
| `Email` | `tickets.user_email` | Implemented + Confirmed | Currently the main driver/user email on the ticket |
| `Phone` / `MobilePhone` | driver phone | Queried, not mapped to local driver sync | Still needs dedicated driver sync design |
| `AccountId` / account relationship | `tickets.company_id` through synced company | Implemented by account linkage | Current company association comes from `record['Account']` in the sync payload |
| Driver status field | driver active/inactive state | Validate in Salesforce | API name and allowed values still not finalized |
| License number/state fields | driver license details | Validate in Salesforce | Not mapped yet |
| Portal access flag | driver account activation/visibility | Pending design decision | Business rule still required |

## Attorney Mapping

### Current Laravel Mapping

Attorneys are currently created from attorney-related fields returned on contact records.

| Salesforce Field | Local Field | Status | Notes |
|---|---|---|---|
| `Attorney_Email_Address__c` | `users.email` | Implemented | Current attorney duplicate-prevention key |
| `Attorney__c` | `users.name` | Implemented | Attorney display name |
| `Attorney_Address__c` | `users.address` | Implemented | Address |
| `Attorney_Number__c` | `users.phone` | Implemented | Phone |
| `Attorney_City__c` | `users.city` | Implemented | City |
| `Attorney_State__c` | `users.state` | Implemented | State |
| `Attorney_Zip__c` | `users.zip` | Implemented | Zip |

### Still Needed

- Confirm whether attorneys truly live in:
  - a custom object
  - `Contact`
  - another relationship object
- If attorney source changes, sync logic should be updated to follow the confirmed source object

## Ticket / Citation Mapping

### Important Current-State Note

The client mapping direction expects a dedicated Ticket/Citation Salesforce object.  
The current Laravel implementation does **not** yet query a separate ticket object. It currently pulls ticket/citation-related fields from `Contact`.

That means the **mapping plan is complete**, but the **final source-object implementation is not yet aligned** with the desired end state.

### Implemented Ticket Mapping

| Salesforce Field | Local Field | Status | Notes |
|---|---|---|---|
| `Id` | `tickets.sf_id` | Implemented | Current duplicate-prevention key |
| `Email` | `tickets.user_email` | Implemented | Driver/user email on ticket |
| `Name` | `tickets.name` | Implemented | Ticket/driver display name |
| `Citation_Type__c` | `tickets.citation_type` | Implemented | Citation type |
| `Driver_Address__c` | `tickets.address` | Implemented | Driver address |
| `Driver_City__c` | `tickets.city` | Implemented | Driver city |
| `Driver_State__c` | `tickets.state` | Implemented | Driver state |
| `Driver_Zip_Code__c` | `tickets.zip` | Implemented | Driver zip |
| `Date_of_Birth__c` | `tickets.birthdate` | Implemented | Birthdate |
| `Date_Issued__c` | `tickets.date_issued` | Implemented | Parsed to local date |
| `Court_Date__c` | `tickets.court_date` | Implemented | Parsed to local date |
| `Court_Name__c` | `tickets.court_name` | Implemented | Court name |
| `Court_Address__c` | `tickets.court_address` | Implemented | Court address |
| `Court_Phone_Number__c` | `tickets.court_phone` | Implemented | Court phone |
| `County__c` | `tickets.county` | Implemented | County |
| `Ticket_Number__c` | `tickets.ticket_number` | Implemented + Confirmed | Ticket number |
| `Dispo__c` | `tickets.ticket_dispo` | Implemented | Legacy disposition field |
| `Roadside_Inspection__c` | `tickets.road_side_inspection` | Implemented | Inspection flag/value |
| `Sales_Agent__c` | `tickets.sales_agent` | Implemented | Sales agent identifier |
| `Account.Sales_Agent_Name__c` | `tickets.sales_agent_name` | Implemented | Sales agent name |
| `Account.Sales_Agent_Email__c` | `tickets.sales_agent_email` | Implemented | Sales agent email |
| `Attorney_Email_Address__c` | `tickets.lawyer_email` | Implemented | Legacy attorney email field |
| `Disposition__c` | `tickets.disposition__c` | Implemented | Used in indicator logic |
| `Confirmed__c` | `tickets.confirmed__c` | Implemented | Used in indicator logic |
| `Canceled__c` | `tickets.canceled__c` | Implemented | Used in indicator logic |
| `DataQ_Number__c` | `tickets.dataq_number__c` | Implemented | DataQ reference |
| `Roadside_Inspection_Number__c` | `tickets.roadside_inspection_number__c` | Implemented | Roadside inspection reference |
| `Ticket_Type__c` | `tickets.ticket_type` | Implemented | Ticket type |
| `Beginning_Fine_Amount__c` | `tickets.beginning_fine_amount` | Implemented | Initial fine amount |
| `Final_Fine_Amount__c` | `tickets.final_fine_amount` | Implemented | Final fine amount |
| `Processor_Name__c` | `tickets.processor_name` | Implemented | Processor name |
| `Processor_Email__c` | `tickets.processor_email` | Implemented | Processor email |
| `Processor_Ph_Number__c` | `tickets.processor_ph_number` | Implemented | Processor phone |
| `Processor_Notes_To_Attorney__c` | `tickets.processor_notes_to_attorney` | Implemented | Processor notes |
| `Total_DVER_Points__c` | `tickets.total_dver_points__c` and points source | Implemented + Confirmed direction | Used as original points source |
| `Total_DVER_Points_REMOVED__c` | `tickets.total_dver_points_removed__c` and points source | Implemented, needs business confirmation on semantics | Current code uses this in the local points formula path |
| `Attorney_response__c` | `tickets.attorney_response` | Implemented | Attorney response |
| `Dispo_Results_From_Attorney__c` | `tickets.road_side_inspection_results` | Implemented | Current result mapping |

### Confirmed But Still Needing Exact API Validation

| Ticket/Citation Concept | Status | Notes |
|---|---|---|
| Citation number | Validate in Salesforce | Expected by business, but exact API name still needs confirmation in live Salesforce |
| Driver relationship field | Validate in Salesforce | Current code relies on contact record itself |
| Company relationship field | Validate in Salesforce | Current code relies on nested `Account` data |
| Attorney relationship field | Validate in Salesforce | Current code relies on attorney custom fields |
| Status / stage field | Validate in Salesforce | Current implementation derives indicator from boolean/disposition fields instead of a formal status object/field |
| Court timezone handling | Validate in Salesforce | Dates are parsed, but timezone/business handling should still be confirmed |

## Ticket Status / Indicator Logic

### Current Implemented Logic

Current local indicator mapping in Laravel:

| Salesforce Fields Evaluated | Local Indicator |
|---|---|
| `Disposition__c` present | `Disposed` |
| `Canceled__c` present | `Canceled` |
| `Confirmed__c` present | `Sent to Attorney` |
| otherwise | `Received` |

### Remaining Governance

- Confirm whether this local indicator model matches the final user-facing status model
- Confirm whether Salesforce has a more authoritative ticket status/stage field that should replace this derived logic

## Points Mapping and Reporting

### Confirmed Direction

- Ticket screens should show:
  - original points
  - final points
  - points saved
- Driver and company summaries should roll up points saved
- Exports and admin monitoring should include point totals

### Current Laravel Implementation

Current formula:

- `Points Saved = Original Points - Final Points`

Current source fields:

- `Total_DVER_Points__c`
- `Total_DVER_Points_REMOVED__c`

Current status:

| Item | Status | Notes |
|---|---|---|
| Ticket-level points display | Implemented | Visible in admin/manager/driver ticket views |
| Driver lifetime points saved | Implemented | Rolled up in driver views |
| Company lifetime points saved | Implemented | Rolled up across hierarchy |
| Parent-company rollups | Implemented | Included in local company rollup logic |
| Salesforce monitor point totals | Implemented | Visible in admin sync monitor |
| Formula edge cases | Pending design decision | Blank, negative, dismissed, overturned, or partial-reduction handling still requires final business confirmation |

## File / Attachment Mapping

### Confirmed Direction

Files should:

1. resolve by linked Salesforce record
2. download from Salesforce content endpoints
3. store on configured Laravel disk or S3
4. retain Salesforce IDs and metadata locally
5. continue supporting legacy `Attachment` records during transition

### Legacy Attachment Mapping

| Salesforce Field | Local Field | Status | Notes |
|---|---|---|---|
| `Id` | `ticket_attachments.sf_id` | Implemented | Duplicate-prevention key |
| `Name` | `ticket_attachments.filename` | Implemented | Filename |
| `Description` | `ticket_attachments.description` | Implemented | Description |
| `LastModifiedDate` | `ticket_attachments.sf_last_modified_date` / local modified info | Implemented | Used for freshness checks |
| `Body` | stored file contents | Implemented | Downloaded through Salesforce API |
| `ParentId` | local ticket relation via `tickets.sf_id` | Implemented | Used to resolve ticket ownership |

### ContentVersion / ContentDocumentLink Mapping

| Salesforce Field / Concept | Local Field / Behavior | Status | Notes |
|---|---|---|---|
| `ContentVersion.Id` | `ticket_attachments.sf_id` | Implemented | Used as duplicate-prevention key |
| `ContentVersion.Title` | `ticket_attachments.filename` base | Implemented | Used as the local filename base |
| `ContentVersion.Description` | `ticket_attachments.description` | Implemented | Description |
| `ContentVersion.LastModifiedDate` | `ticket_attachments.sf_last_modified_date` | Implemented | Freshness |
| `ContentVersion.VersionData` | file contents source | Implemented | Used for binary download |
| `ContentVersion.ContentUrl` | external link fallback | Implemented | Used when content is link-style and size is empty |
| `ContentVersion.ContentDocumentId` | lookup into `ContentDocumentLink` | Implemented | Used to resolve parent relationship |
| `ContentDocumentLink.LinkedEntityId` | `ParentId` surrogate for local sync | Implemented | Current code keeps only linked IDs beginning with `003` |

### Important Current Limitation

The current `ContentDocumentLink` mapping keeps only `LinkedEntityId` values that start with `003`, which means the file sync is currently assuming a `Contact`-linked file path.  
If the final Salesforce ticket/citation object is different, this logic will need to be adjusted to the confirmed ticket object relationship.

## Duplicate Prevention Keys

| Local Record | Current Key | Status |
|---|---|---|
| Company | `companies.sf_id = Account.Id` | Implemented |
| Ticket | `tickets.sf_id = Contact.Id` in current code | Implemented, but may need to change if final ticket object is separate |
| Attorney | `users.email = Attorney_Email_Address__c` | Implemented |
| Attachment | `ticket_attachments.sf_id = Attachment.Id or ContentVersion.Id` | Implemented |

## What Is Completed

The following are now complete at the mapping-document level:

- overall Salesforce integration direction
- object-to-system mapping plan
- implemented Laravel field mappings
- attachment mapping direction
- duplicate-prevention strategy
- points mapping direction
- required confirmation checklist

## What Still Requires Live Salesforce Validation

These items are **not** blocked by missing documentation anymore, but they are still blocked by lack of live Salesforce verification:

1. Exact API name of the final ticket/citation object
2. Exact API names for:
   - company hierarchy field if not `ParentId`
   - company status field
   - driver status field
   - license fields
   - portal access flag
   - citation number field
   - driver relationship field
   - company relationship field
   - attorney relationship field
   - official status/stage field
   - original points field if different from current implementation
   - final points field if different from current implementation
3. Whether attorneys are sourced from:
   - a custom object
   - contact
   - another relationship object
4. Whether the parent account can also act as an operating trucking company with its own tickets/drivers
5. Whether the current points formula should change for edge cases
6. Whether the current ticket source should move from `Contact` to a dedicated ticket/citation object
7. Whether `SystemModstamp` should replace `LastModifiedDate` in the live incremental sync implementation

## Practical Conclusion

### Mapping Completion Status

- Mapping document and implementation-direction work: complete
- Laravel current-state mapping documentation: complete
- Live Salesforce field/API validation: not complete yet
- Final production-ready Salesforce sync alignment: not complete yet

### Recommendation

Use this document as the completed project-side mapping sheet, then perform one live Salesforce validation pass to finalize:

- exact API names
- final object sources
- final ticket source object
- final hierarchy field
- final point-source field approval
