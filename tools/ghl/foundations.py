#!/usr/bin/env python3
"""Step 1 of the GHL build: tags + contact custom fields for the I AM Mastermind sub-account.

Idempotent: anything that already exists (same name) is left alone.
Auth: the Authorization header is added by the session's network proxy, so the
token never appears here. Run:  python3 tools/ghl/foundations.py [--dry-run]
"""
import json, sys, urllib.request, urllib.error

LOC = "kbrBFckLArjXDxIu6Hnl"
API = "https://services.leadconnectorhq.com"
DRY = "--dry-run" in sys.argv

TAGS = [
    # sent by the website forms (theme inc/ghl.php)
    "website-lead", "form-newsletter", "form-contact", "form-challenge",
    "form-assessment", "form-application", "form-quiz", "form-longevity-waitlist",
    "form-research", "form-speaker", "lang-en", "lang-es",
    # customer journey stages (strategy: Lead -> trial -> buyer -> applicant)
    "stage-lead", "stage-growth-society-trial", "stage-program-buyer",
    "stage-retreat-applicant", "stage-inner-circle-applicant",
    # program access / buyers
    "program-scaling-for-life", "program-escalando-en-la-vida",
    "program-business-matrix", "program-matriz-de-negocios",
    "program-mastery-lifestyle", "program-inner-circle",
    "program-growth-society", "program-retreat",
    # nurture tracks + lists
    "interest-mind", "interest-body", "waitlist-longevity", "waitlist-retreat",
    "past-client", "migrated-member",
]

PROGRAMS = ["Growth Society", "Scaling For Life", "Escalando en la Vida", "Business Matrix",
            "Matriz de Negocios", "Mastery Lifestyle", "Inner Circle", "Retreats", "Longevity"]

FIELDS = [
    {"name": "Preferred Language", "dataType": "SINGLE_OPTIONS", "options": ["English", "Español"]},
    {"name": "Program of Interest", "dataType": "SINGLE_OPTIONS", "options": PROGRAMS},
    {"name": "Source Page", "dataType": "TEXT", "placeholder": "Website page the lead came from"},
    {"name": "Form Message", "dataType": "LARGE_TEXT", "placeholder": "Message from the website form"},
    {"name": "Application Answers", "dataType": "LARGE_TEXT", "placeholder": "Retreat / Inner Circle application answers"},
    {"name": "Referred By", "dataType": "TEXT", "placeholder": "Referral code or affiliate"},
    {"name": "Legacy Courses", "dataType": "LARGE_TEXT", "placeholder": "Courses the member had on the old platform"},
]


def call(method, path, body=None):
    req = urllib.request.Request(API + path, method=method,
                                 data=json.dumps(body).encode() if body is not None else None,
                                 headers={"Version": "2021-07-28", "Accept": "application/json",
                                          "Content-Type": "application/json",
                                          "User-Agent": "iam-ghl-setup/1.0 (+curl-compatible)",
                                          "Authorization": "Bearer placeholder"})  # proxy replaces it
    try:
        with urllib.request.urlopen(req, timeout=30) as r:
            return r.status, json.loads(r.read() or b"{}")
    except urllib.error.HTTPError as e:
        return e.code, {"error": e.read().decode()[:300]}


def main():
    _, t = call("GET", f"/locations/{LOC}/tags")
    have_tags = {x["name"].lower() for x in t.get("tags", [])}
    _, f = call("GET", f"/locations/{LOC}/customFields")
    have_fields = {x["name"].lower(): x for x in f.get("customFields", [])}
    ok = err = skip = 0
    for name in TAGS:
        if name.lower() in have_tags:
            skip += 1; continue
        code, res = (200, {}) if DRY else call("POST", f"/locations/{LOC}/tags", {"name": name})
        print(f"tag   {code}  {name}", "" if code < 300 else res)
        ok, err = (ok + 1, err) if code < 300 else (ok, err + 1)
    for fd in FIELDS:
        if fd["name"].lower() in have_fields:
            skip += 1; continue
        body = dict(fd, model="contact")
        code, res = (200, {}) if DRY else call("POST", f"/locations/{LOC}/customFields", body)
        key = res.get("customField", {}).get("fieldKey", "")
        print(f"field {code}  {fd['name']}  {key}", "" if code < 300 else res)
        ok, err = (ok + 1, err) if code < 300 else (ok, err + 1)
    print(f"created {ok}, already existed {skip}, failed {err}")
    return 1 if err else 0


if __name__ == "__main__":
    sys.exit(main())
