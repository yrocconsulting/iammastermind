# Content to review (website direction update, 2026-11-25)

Everything below is **draft copy** written to match the strategy proposal and the
2026-11-25 call notes. It is live on staging only. Abraham (and Heidi for the
retreats) should confirm or correct each item before it goes to the live site.
Health and research copy follows the call's guardrails: technology and lifestyle
language only, no treatment or medical claims, no peptides, partners not named.

## Structure (done)
- Menu: Home · **Mind** (coaching programs, unchanged dropdown) · **Body** (Longevity, Retreats and their pages, Live Events) · **Research** (Alexandria R&D Center) · **About** (Our Story, Abraham Carranza, Speaking) · Blog · Contact. EN + ES.
- "The Mastermind Ascension" renamed everywhere it is visible: **The I AM Retreat** in sentences, **I AM Retreats** in page titles and the retreat logo, **The Retreat / El Retiro** in the retreat menu. Page addresses (e.g. `/retreats/the-mastermind-ascension`) and image files are unchanged so links keep working. *Confirm the name "The I AM Retreat".*

## Home page
- New "Where do you want to start?" section with three doors (Mind / Body / Research).
- New "Longevity — coming soon" card under the program cards. **Needs:** the telehealth site URL (Settings → I AM Mastermind → Longevity / telehealth site URL). Until set, it links to the on-site Longevity waitlist page.
- "Growth Society" links to the existing Scaling For Life page, as on the original site. *Confirm, or send the Growth Society book club page/trial link.*

## About page
- New section after "Our Story": "From mindset to measurable change" (neuroscience + "bring your body with you").

## New pages (EN/ES)
| Page | URL | To confirm |
|---|---|---|
| Longevity | `/longevity` | The three pillars (biohacking, measurement, mindset); "brain mapping before and after" and "90-day integration" at retreats; waitlist wording |
| Alexandria R&D Center | `/alexandria-research-center` | Description of brain mapping and at-home neurofeedback; "with participants' consent, we record brain and body signals during the six days"; which partners/software (if any) can be named, and when |
| Abraham Carranza | `/abraham-carranza` | Bio narrative (builder → coach → researcher → body); facts card: Founder & CEO, English/Spanish, **20+ years** (from the retreat pages), **based in Texas**; his official profile links for Google's knowledge panel (Settings → Abraham's official profiles) |
| Speaker | `/speaker` | The three signature talk titles and descriptions; formats offered; "reply within two business days" |

## Forms on the new pages → GHL
| Form | Tags added |
|---|---|
| Longevity waitlist | `form-longevity-waitlist`, `waitlist-longevity`, `interest-body`, `stage-lead`, `lang-*` |
| Research information request | `form-research`, `stage-lead`, `lang-*` |
| Speaker booking | `form-speaker`, `stage-lead`, `lang-*` |

## Found along the way
- The site's structured data (Organization `sameAs`) lists placeholder social profiles (`…/ruta-del-cliente`) inherited from the original build. They should be replaced with the real Instagram, YouTube, LinkedIn, etc.
- All images are served from two Cloudinary accounts the old team controls. Before cut-over, copy them into the WordPress media library or a Cloudinary account the business owns.

## Not done yet (waiting on decisions)
- Coaches and consultants page (PGI track not confirmed).
- Program buttons → GHL checkout (waiting on GHL offers and confirmed prices).
- Growth Society two-week trial link (waiting on the offer).
- The hidden `/quiz` page from the old site.
