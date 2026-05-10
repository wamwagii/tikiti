# 🎫 Tikiti - Event Ticketing Platform

[![Laravel Version](https://img.shields.io/badge/Laravel-13.x-red.svg)](https://laravel.com)
[![Filament Version](https://img.shields.io/badge/Filament-5.x-purple.svg)](https://filamentphp.com)
[![SQLite](https://img.shields.io/badge/SQLite-3.x-blue.svg)](https://sqlite.org)
[![Paystack](https://img.shields.io/badge/Paystack-API-green.svg)](https://paystack.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A modern, full-featured event ticketing platform built for the Kenyan market, supporting **Sportpesa Premier League** football matches and **international concerts** with secure M-Pesa and card payments via Paystack.

## 📋 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Technology Stack](#-technology-stack)
- [Prerequisites](#-prerequisites)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Database Setup](#-database-setup)
- [Admin Panel](#-admin-panel)
- [Payment Integration](#-payment-integration)
- [Frontend Features](#-frontend-features)
- [API Endpoints](#-api-endpoints)
- [User Roles](#-user-roles)
- [Testing](#-testing)
- [Troubleshooting](#-troubleshooting)
- [Deployment](#-deployment)
- [Support](#-support)

## 🌟 Overview

Tikiti Kenya is a complete ticketing solution that allows event organizers to sell tickets online and customers to purchase tickets securely. The platform supports:

- **Football Matches** - Sportpesa Premier League and local derbies
- **Concerts** - International and local artist performances
- **Special Events** - Holidays, festivals, and corporate events

### Key Highlights

- ✅ **Guest Checkout** - Purchase tickets without creating an account
- ✅ **Instant Digital Tickets** - QR code tickets delivered immediately
- ✅ **M-Pesa Integration** - Kenya's most popular mobile payment method
- ✅ **Card Payments** - Visa and Mastercard support via Paystack
- ✅ **Admin Dashboard** - Full control with Filament admin panel
- ✅ **Mobile Responsive** - Works perfectly on all devices

## 🚀 Features

### 🎯 Core Features

| Feature | Description |
|---------|-------------|
| **Event Management** | Create, edit, and manage events with multiple ticket tiers (VIP, Regular, Terrace) |
| **Ticket Sales** | Sell tickets for football matches, concerts, and special events |
| **Secure Payments** | Integrated Paystack payment gateway (M-Pesa & Card payments) |
| **User Management** | Role-based access (Admin, Event Manager, Customer) |
| **Guest Checkout** | Purchase tickets without creating an account |
| **Digital Tickets** | Instant ticket generation after successful payment |
| **Image Upload** | Event images with automatic storage handling |
| **Email Notifications** | Automatic confirmation emails with ticket details |

### 🎨 Admin Panel (Filament)

| Module | Capabilities |
|--------|--------------|
| **Dashboard** | Real-time statistics, sales charts, and analytics |
| **Events** | 6-step wizard form, event management, ticket tiers |
| **Venues** | Manage venues, capacities, amenities, contact info |
| **Orders** | Track orders, payment status, customer details |
| **Tickets** | View generated tickets, status tracking, validation |
| **Users** | Manage accounts, roles, permissions, disable accounts |

### 🏟️ Frontend Features

- **Responsive Design** - Mobile-friendly interface with Tailwind CSS
- **Event Discovery** - Browse events by category (Football, Concerts, Holidays)
- **Featured Events** - Highlight important events on homepage
- **Venue Directory** - View popular venues with event counts
- **Secure Checkout** - 3-step checkout process
- **Payment Success/Failure Pages** - Clear payment status feedback

## 💻 Technology Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| **Laravel** | 13.x | Backend Framework |
| **Filament** | 5.x | Admin Panel |
| **SQLite** | 3.x | Database (Development) |
| **Paystack** | API | Payment Gateway |
| **Tailwind CSS** | 3.x | Frontend Styling |
| **Livewire** | 3.x | Dynamic UI Components |
| **PHP** | 8.5+ | Server-side Language |

## 📋 Prerequisites

Before installing, ensure you have:

