# 🎫 Tikiti Kenya - Event Ticketing Platform

[![Laravel Version](https://img.shields.io/badge/Laravel-13.x-red.svg)](https://laravel.com)
[![Filament Version](https://img.shields.io/badge/Filament-5.x-purple.svg)](https://filamentphp.com)
[![SQLite](https://img.shields.io/badge/SQLite-3.x-blue.svg)](https://sqlite.org)
[![Paystack](https://img.shields.io/badge/Paystack-API-green.svg)](https://paystack.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A modern, full-featured event ticketing platform built for the Kenyan market, supporting Sportpesa Premier League football matches and international concerts with secure M-Pesa and card payments via Paystack.

## 📋 Table of Contents

- [Features](#-features)
- [Technology Stack](#-technology-stack)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Database Setup](#-database-setup)
- [Admin Panel](#-admin-panel)
- [Payment Integration](#-payment-integration)
- [Project Structure](#-project-structure)
- [API Endpoints](#-api-endpoints)
- [Troubleshooting](#-troubleshooting)
- [Deployment](#-deployment)

## 🌟 Features

### 🎯 Core Features
- **Event Management** - Create, edit, and manage events with multiple ticket tiers
- **Ticket Sales** - Sell tickets for football matches, concerts, and special events
- **Secure Payments** - Integrated Paystack payment gateway (M-Pesa & Card payments)
- **User Management** - Role-based access (Admin, Event Manager, Customer)
- **Guest Checkout** - Purchase tickets without creating an account
- **Digital Tickets** - Instant ticket generation after successful payment
- **Image Upload** - Event images with automatic storage handling

### 🎨 Admin Panel (Filament)
- **Dashboard** - Real-time statistics and analytics
- **Event Management** - Create events with 6-step wizard form
- **Venue Management** - Manage venues, capacities, and amenities
- **Order Management** - Track orders and payment status
- **Ticket Management** - View and manage sold tickets
- **User Management** - Manage user accounts, roles, and permissions

### 🏟️ Frontend Features
- **Responsive Design** - Mobile-friendly interface with Tailwind CSS
- **Event Discovery** - Browse events by category (Football, Concerts)
- **Featured Events** - Highlight important events
- **Venue Directory** - View popular venues with event counts
- **Secure Checkout** - 3-step checkout process
- **Email Notifications** - Payment confirmation and tickets via email

## 🚀 Technology Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| Laravel | 13.x | Backend Framework |
| Filament | 5.x | Admin Panel |
| SQLite | 3.x | Database (Development) |
| Paystack | API | Payment Gateway |
| Tailwind CSS | 3.x | Frontend Styling |
| Livewire | 3.x | Dynamic UI Components |
| PHP | 8.5+ | Server-side Language |

## 📋 Prerequisites

- PHP >= 8.5 with extensions: `sqlite3`, `curl`, `json`, `openssl`, `mbstring`, `fileinfo`
- Composer
- Node.js & NPM (optional, for asset compilation)
- SQLite3 (for development)
- Paystack account (for payment integration)

## 🛠️ Installation

### Step 1: Clone the Repository

```bash
git clone https://github.com/wamwagii/tikiti.git
cd tikiti