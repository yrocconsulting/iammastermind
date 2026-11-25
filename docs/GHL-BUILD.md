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

## Next
2. Pipelines · 3. Products & prices · 4. Course structures · 5. Build sheets for
offers, checkout, portal, Communities and workflows (no API for those).
