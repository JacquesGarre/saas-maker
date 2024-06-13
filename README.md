# Symfony PHP Project Template with Docker

## Overview

This is a template repository for starting Symfony PHP projects with Docker, using PHP-FPM and Nginx as the web server. There is no database container included.

## Requirements

- Git version 2.25+ (git --version)
- Docker version v24+ (docker -v)
- Docker Compose version v2.21+ (docker compose version)
- PHP 8.3+ (php --version)
- Composer version 2.3+ (composer -V)

 
## Installation

#### 1. Setup
- `git clone git@github.com:JacquesGarre/saas-maker.git`
- `cd saas-maker`
- `cp .env.example .env.local`

#### 2. Start 
- Start docker daemon
- `composer start`
- Go to http://localhost:8000
