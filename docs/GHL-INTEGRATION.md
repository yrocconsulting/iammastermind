# Go High Level integration

## Already built into the theme

Settings → **I AM Mastermind**. Any value can be set in `wp-config.php` instead, e.g.
`define( 'IAM_GHL_TOKEN', 'pit-…' );`. That keeps secrets out of the database.

### 1. Website forms → GHL contacts

All original forms go through one pipeline (`inc/forms.php`):

| Form | Page(s) | Endpoint | `form_type` |
|---|---|---|---|
| 30-Day Free Trial (name, phone, email) | footer of every main page | `/api/newsletter` | `newsletter` |
| Contact (name, email, interest, message) | /contact | `/api/contact` | `contact` |
| 72-Hour Challenge | /challenge | `/api/contact` | `challenge` |
| Retreat assessment (archetypes, baseline, outcomes…) | /retreats/apply | `/api/contact` | `assessment` |
| Inner Circle application | /inner-circle | POST to page | `application` |

You can send leads to GHL in two ways. Use either one, or both:

**A. Inbound Webhook (recommended to start; no API key).**
In GHL: *Automation → Workflows → New → trigger "Inbound Webhook"*. Copy the URL into
**Inbound Webhook URL**. Click **Send test lead** on the settings page so GHL can see the
fields, then map them in a *Create/Update Contact* action. Branch on `form_type` to add tags,
send to a pipeline, notify the coach, start nurture emails/SMS, etc.

Payload:
```json
{ "form_type": "contact", "name": "…", "first_name": "…", "last_name": "…",
  "email": "…", "phone": "…", "source": "…", "message": "…", "lang": "en|es",
  "page_url": "…", "tags": "website-lead,form-contact,lang-en",
  "submitted_at": "2026-11-04T17:00:00+00:00", "fields": { "…every submitted field…" } }
```

**B. API v2 (Private Integration token).**
In GHL: *Settings → Private Integrations → Create* with scopes `contacts.write` and
`contacts.readonly`. Enter the token and your **Location ID**. Each lead is upserted as a
contact with tags `website-lead` (editable), `form-<type>` and `lang-<en|es>`. All extra
answers (assessment checkboxes, message, interest) are added as a contact note. Tag-based
workflows in GHL then run on their own.

Every submission is also saved in WordPress (**Form submissions**), along with the GHL
response, so nothing is lost if GHL is down or misconfigured.

### 2. Member area → GHL Memberships

Set **Membership portal URL** to your GHL Memberships / Client Portal domain. "Member Login"
(`/login`) and `/dashboard`, `/reset-password` then redirect there. Until it is set, `/login`
goes to the WordPress login.

Tip: in GHL, give the portal a custom subdomain like `members.iammastermind.com`, so members
stay on your brand.

### 3. Tracking and chat

- **External tracking ID** (GHL → Settings → External Tracking): loads GHL's tracking script,
  so page visits show on the contact timeline after they opt in.
- **Extra footer code**: paste the GHL **Chat Widget** snippet (Sites → Chat Widget → Get Code).

## Other options (not built yet)

| Integration | What it gives you | Effort |
|---|---|---|
| GHL **Calendars** embed on /contact or program pages | Book discovery calls straight into the coach's calendar | Paste the embed; small styling pass |
| GHL **Payments / Order forms** instead of Stripe links | Purchases create contacts, start onboarding, and give course access automatically | Rebuild the checkout links |
| Purchase → **Membership offer** automation | Buying a program gives instant access to its course in GHL Memberships | Workflow only (GHL side) |
| Lead **pipeline** per program | Inner Circle / retreat applications go into a sales pipeline with stages | Workflow only |
| Official **LeadConnector WordPress plugin** | Lets you embed GHL forms, surveys and funnels with shortcodes | Install plugin. Not needed for the current forms |
| Events → GHL | Show events from GHL calendars on /events | Custom code (API v2 calendars) |

Hooks for developers: `iam_lead` (change a lead before it is sent),
`iam_ghl_contact`, `iam_ghl_tags`, `iam_ghl_webhook_payload`, `iam_lead_processed`.
