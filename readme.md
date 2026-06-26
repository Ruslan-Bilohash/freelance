# Freelance CMS

Universal **PHP freelance marketplace** script from [bilohash.com](https://bilohash.com/) — Norway, Europe and Ukraine. Demo platform in the style of Upwork/Fiverr with projects, proposals, freelancer registration and admin panel.

## Live demo

| Page | URL |
|------|-----|
| Marketplace | https://bilohash.com/freelance/ |
| Become a freelancer | https://bilohash.com/freelance/become-freelancer.php |
| Regions (SEO) | https://bilohash.com/freelance/regions.php |
| Oslo SEO | https://bilohash.com/freelance/oslo/ |
| Admin | https://bilohash.com/freelance/admin/ |
| Product / sales | https://bilohash.com/freelance/site/ |

**Admin:** `demo` / `bilofreelance2026`  
**Users:** `anna@demo.no`, `ole@demo.no` — password `demo2026`

## Languages

Norwegian (default), English, Ukrainian, Russian — public site, admin panel, contact form, SEO regions.

## Key features

- **Projects & proposals** — 12 demo jobs (web, WordPress, Elementor, AI, mobile, marketing)
- **Freelancers** — Simple / Pro tiers, admin activation, public profiles
- **Clickable skills** — tags link to search (`Elementor`, `WordPress`, `Copywriting`, etc.)
- **Header** — compact nav with **categories dropdown** (phone / tablet / desktop)
- **SEO** — 7 Norwegian cities + Europe + Ukraine landing pages, Schema.org, sitemap
- **Settings** — accent colour, button colour, background image
- **Integrations** — Google reCAPTCHA v2, AI chat widget (Grok xAI or OpenAI GPT)
- **Design** — dark navy + emerald green, 2px sharp corners, fully responsive

## Admin settings

`Admin → Settings`:

1. **General** — tiers, proposal limits, activation rules  
2. **Appearance** — site accent, button colours, background colour/image  
3. **Integrations** — reCAPTCHA site/secret keys; chat provider (Grok / GPT) + API key + sales instructions  
4. **SEO** — extra selling paragraph for homepage conversion  

### reCAPTCHA

Get keys at [Google reCAPTCHA](https://www.google.com/recaptcha/admin). Paste site + secret key in admin. Leave empty to use bilohash.com defaults.

### AI chat (Grok / GPT)

1. Enable chat in admin  
2. Choose **Grok xAI** or **OpenAI GPT**  
3. Add API key (x.ai or OpenAI)  
4. Write **system instructions** — e.g. sell Freelance CMS, mention demo tiers, Norwegian SEO, link to order page  

Widget loads `https://bilohash.com/chat-widget.js` (configure `bot.php` endpoint in production).

## Installation

```text
/freelance/
  data/          ← writable (projects.json, freelancers.json, users.json, settings.json)
  lang/          ← no.php, en.php, uk.php, ru.php
  admin/
  assets/
```

Requirements: PHP 8+, `mod_rewrite` (optional clean region URLs), HTTPS recommended.

## SEO selling points

- Multilingual hreflang (NO/EN/UK/RU)  
- City pages: Oslo, Bergen, Stavanger, Trondheim, Drammen, Kristiansand, Tromsø, Europe, Ukraine  
- `become-freelancer.php` landing with FAQ + Schema.org  
- Internal linking via skill tags and region footer  
- `llms.txt` + `sitemap.php` for crawlers and AI  

## Order development

Custom freelance job board, escrow, payments (Vipps/Stripe), client accounts:

**https://bilohash.com/**

## Related products

- [Booking CMS](https://bilohash.com/booking/site/)
- [Auction CMS](https://bilohash.com/auction/site/)

---

© Freelance CMS Demo — bilohash.com
