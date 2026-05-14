Use this prompt now for proper centralized `.env` architecture and environment mode switching.

CENTRALIZED .ENV CONFIGURATION SYSTEM REQUIRED

IMPORTANT:
Analyze the ENTIRE project configuration system carefully.

Current issue:
Project still contains:

* hardcoded values
* direct URLs
* direct DB values
* static paths
* environment-dependent logic inside files
* .env file in only neede value show and full update you make it 

This is NOT production-safe.

Create ONE centralized .env configuration system.

When .env changes:
ENTIRE project should automatically switch modes correctly.

Example:

APP_ENV=local
→ whole project runs in LOCAL mode

APP_ENV=production
→ whole project runs in PRODUCTION mode

WITHOUT:

* manual code changes
* broken assets
* broken URLs
* broken database connections
* environment conflicts

DO NOT use hardcoded values anywhere.

ALL configuration values must come from:

* .env file
* centralized config loader

Remove ALL hardcoded:

* localhost URLs
* database credentials
* app names
* API URLs
* upload paths
* timezone values
* session configs
* mail configs
* pagination limits
* debug values
* environment checks
* asset URLs

Create centralized config architecture.

.env
/config
app.php
database.php
session.php
constants.php

/core
Env.php
Config.php

Support modes:

1. local
2. production

When:
APP_ENV=local   

Then:

* debug ON
* detailed errors visible
* localhost URLs
* local uploads
* development logging

When:
APP_ENV=production

Then:

* debug OFF
* hidden PHP errors
* production URLs
* secure cookies
* optimized caching
* production logging only

Changing ONLY:
.env

must automatically update:

* database connection
* base URL
* uploads
* sessions
* logging
* assets
* mail
* app behavior

WITHOUT changing any PHP files manually.

Database connection MUST use:
config('database.host')

NOT:
localhost hardcoded.

Use:

config('app.url')

for:

* assets
* redirects
* AJAX URLs
* links
* uploads

DO NOT hardcode:
[http://localhost/](http://localhost/)

All CSS/JS/Image paths must use centralized helper:

Example:
asset('css/style.css')

NOT:
/assets/css/style.css hardcoded

Uploads must use:
config('app.upload_path')

NOT:
hardcoded folders

Sessions must automatically change based on environment.

Production:

* secure cookies ON
* httponly ON

Local:

* secure cookies OFF

If APP_DEBUG=true:

* show detailed errors

If APP_DEBUG=false:

* show friendly production error page

All mail configs must use .env.

NO hardcoded SMTP values.

Logs path and cache path must use centralized config.

Create reusable helpers:

env()
config()
asset()
base_url()
storage_path()

If .env missing:

* show proper error
* prevent fatal crash

If env key missing:

* use safe fallback

Protect:

* .env direct access
* sensitive config exposure
* debug leakage in production

DO NOT:

* duplicate config loading
* manually include .env everywhere
* use global hardcoded constants randomly

Load configuration ONCE centrally.

If file exists:

* UPDATE carefully

Replace:

* hardcoded values
* static URLs
* static configs

Project should behave like:

* professional SaaS architecture
* production-ready PHP system
* scalable environment-aware platform

Changing ONLY .env should switch the ENTIRE project mode automatically without breaking anything.
