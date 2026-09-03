# EVSolutions Website

A focused French-language client website presenting electric-vehicle charging solutions in Morocco and guiding visitors toward a quotation request.

**Live website:** [evsolutions.ma](https://evsolutions.ma)

> **Status:** delivered client website. This repository demonstrates a small production website, not a SaaS product or charging-management platform.

## Purpose

The website gives residential and professional visitors a clear path through:

- EV charging equipment presentation;
- installation-oriented service information;
- use cases for homes, companies, hotels, residences, and fleets;
- telephone and WhatsApp contact actions;
- a server-validated quotation form.

Claims, certifications, product characteristics, and commercial details should be changed only when supported by current client documentation.

## Technology

- semantic HTML5;
- responsive CSS;
- small vanilla JavaScript interactions;
- PHP 8 contact endpoint;
- JSON-LD and standard page metadata;
- no framework, database, build chain, or runtime dependency manager.

## Repository structure

```text
index.html    page content, metadata, and structured data
style.css     layout, visual system, and responsive behavior
script.js     navigation, reveal behavior, and form interaction
contact.php   server-side validation and contact delivery
```

## Local review

The static interface can be previewed with:

```bash
python -m http.server 8080
```

Open `http://localhost:8080`. The PHP form requires a PHP-capable server and must be tested separately.

## Deployment

The production version is designed for a standard cPanel document root:

1. upload `index.html`, `style.css`, `script.js`, and `contact.php`;
2. select a supported PHP 8 version;
3. verify CSS and JavaScript assets;
4. submit the quotation form from the public HTTPS domain;
5. verify the JSON response, message delivery, and spam controls;
6. check the canonical URL and structured-data values.

## Configuration

Before a deployment or ownership change, review:

- the canonical production URL in `index.html`;
- public telephone and WhatsApp links;
- structured-data contact values;
- the destination address and sender policy in `contact.php`;
- SPF, DKIM, and DMARC for the sending domain.

Keep operational addresses and contact details in the source only when the client has approved their public use.

## Security and maintenance

- The server endpoint validates required fields and returns JSON.
- Hosting-level rate limiting and authenticated SMTP are preferable to relying only on PHP `mail()`.
- No analytics, testimonials, certifications, performance numbers, or distribution claims should be added without evidence and client approval.
- Production form behavior must be retested after PHP, DNS, or email-policy changes.

## Scope boundary

This repository contains a simple commercial presentation website. It does not control chargers, process payments, manage energy consumption, provide remote diagnostics, or claim regulatory approval beyond evidence explicitly supplied by the client.

## License

Client-facing source code is not licensed for public reuse. All rights reserved.
