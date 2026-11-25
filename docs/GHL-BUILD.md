# GHL sub-account build log

Sub-account: **I AM Mastermind** (`kbrBFckLArjXDxIu6Hnl`), timezone US/Central.
API access: Private Integration token stored as a network secret on the
"I AM Mastermind (YROC)" cloud environment (never in this repo).
Scripts in `tools/ghl/` are idempotent: re-running skips what already exists.

## Step 1 — Foundations (done 2026-11-25) · `tools/ghl/foundations.py`

**Tags (28)**

| Group | Tags |
|---|---|
| Sent by website forms | `website-lead`, `form-newsletter`, `form-contact`, `form-challenge`, `form-assessment`, `form-application`, `form-quiz`, `lang-en`, `lang-es` |
| Journey stage | `stage-lead`, `stage-growth-society-trial`, `stage-program-buyer`, `stage-retreat-applicant`, `stage-inner-circle-applicant` |
| Program access | `program-scaling-for-life`, `program-escalando-en-la-vida`, `program-business-matrix`, `program-matriz-de-negocios`, `program-mastery-lifestyle`, `program-inner-circle`, `program-growth-society`, `program-retreat` |
| Nurture & lists | `interest-mind`, `interest-body`, `waitlist-longevity`, `waitlist-retreat`, `past-client`, `migrated-member` |

**Contact custom fields (7)**

| Field | Type | Key |
|---|---|---|
| Preferred Language | Dropdown: English, Español | `contact.preferred_language` |
| Program of Interest | Dropdown: Growth Society, Scaling For Life, Escalando en la Vida, Business Matrix, Matriz de Negocios, Mastery Lifestyle, Inner Circle, Retreats, Longevity | `contact.program_of_interest` |
| Source Page | Text | `contact.source_page` |
| Form Message | Long text | `contact.form_message` |
| Application Answers | Long text | `contact.application_answers` |
| Referred By | Text | `contact.referred_by` |
| Legacy Courses | Long text | `contact.legacy_courses` |

Note: GHL's API sits behind Cloudflare, which rejects Python's default
user agent (error 1010). Scripts send their own `User-Agent`.

## Website → GHL mapping (theme `inc/ghl.php`, live from 2026-11-25)

Every website form submission (API mode) upserts the contact with:

| Tag / field | Value |
|---|---|
| Tags | `website-lead`, `form-<type>`, `lang-en`/`lang-es`, `stage-lead`; plus `stage-retreat-applicant` (retreat assessment), `stage-inner-circle-applicant` (Inner Circle application), and `interest-body` (Retreats/Longevity) or `interest-mind` (any other program) |
| Preferred Language | English / Español, from the site language |
| Source Page | URL of the page the form was sent from |
| Program of Interest | Matched from the form's interest field or the page URL; left empty when unclear |
| Form Message | The message text, when the form has one |
| Application Answers | All answers, for retreat assessments and Inner Circle applications |

All answers are also added as a contact note. In webhook mode the same values
are sent in a `custom` object. API note: custom field keys are sent without
their `contact.` prefix (e.g. `preferred_language`); with the prefix GHL ignores them.

Verified 2026-11-25 with test contacts (created, checked, deleted).

## Step 2 — Pipelines (blocked: token lacks the pipeline-create scope)

Planned, to create by API once the scope is added, or by hand under
Opportunities → Pipelines → Create:

**Program Sales:** New Lead → Nurturing → Growth Society Trial → Offer Sent → Program Buyer
**High-Ticket Applications** (Retreats, Inner Circle): Application Received → Call Booked → Call Completed → Proposal Sent → Enrolled
**Member Migration** (the 30 legacy members): Imported → Invite Sent → Logged In → Active

## Next
3. Products & prices · 4. Course structures · 5. Build sheets for
offers, checkout, portal, Communities and workflows (no API for those).
