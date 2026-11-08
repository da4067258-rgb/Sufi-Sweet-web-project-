<h1 align="center">
  <img src="images/logo.png" alt="Sufi Sweets logo" width="80"><br>
  Sufi Sweets — E‑Commerce Frontend
</h1>

<p align="center">
  <strong>Advanced Web Programming · Assignment 1</strong><br>
  Traditional sweets &amp; dairy products · Since 1994
</p>

<p align="center">
  <img src="https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white" alt="HTML5">
  <img src="https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white" alt="CSS3">
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black" alt="JavaScript">
  <img src="https://img.shields.io/badge/Bootstrap_5.3-7952B3?style=flat&logo=bootstrap&logoColor=white" alt="Bootstrap 5.3">
  <img src="https://img.shields.io/badge/Status-Assignment_1_Complete-success" alt="Status">
  <img src="https://img.shields.io/badge/Backend-Not_Implemented-lightgrey" alt="Frontend only">
</p>

---

## About the Project

**Sufi Sweets** is a traditional sweet shop and dairy products business based in Lahore, Pakistan, operating since 1994. This repository contains the **frontend UI templates** for its e‑commerce website, developed as **Assignment 1** of the Advanced Web Programming course.

The website provides a complete, professional, and fully responsive user experience where customers can browse products, add items to a cart, view their dashboard, and manage their account — all in a clean, consistent interface.

> ⚠️ **Scope:** This is a **frontend‑only** phase. There is no backend, database, or real authentication. Cart data is stored in `localStorage` purely to demonstrate the UI. Backend integration is planned for the next phase of the semester project.

---

## ✨ Features at a Glance

| Feature | Description |
|---|---|
| **Homepage** | Hero, categories, best sellers, testimonials, newsletter signup |
| **Shop** | 12 products, category filters, live search, quick‑view modal, bulk price table |
| **Cart** | Live quantity controls, remove items, coupon (`SUFI10`), clear‑cart confirmation modal |
| **Checkout** | Delivery form + payment options, all fields validated |
| **Dashboard** | Stat cards, Orders/Profile/Addresses tabs, order‑details / new‑order / add‑address modals |
| **Login & Register** | Full client‑side validation, show/hide password, password match check |
| **Contact** | Contact form + FAQ accordion |
| **About** | Story, timeline, values, team |
| **Fully Responsive** | Tested at 375 px / 768 px / 1440 px |
| **Consistent Design** | Shared colour system, typography and component styles |

---

## Tech Stack

| Layer | Technology |
|---|---|
| **Markup** | HTML5 (semantic structure) |
| **Styling** | CSS3 (custom design system, gradients, media queries) |
| **Framework** | [Bootstrap 5.3](https://getbootstrap.com/) (via CDN — grid, components, utilities) |
| **Scripting** | JavaScript ES6 (form validation, cart logic, filters, modals, toasts) |
| **Storage** | `localStorage` (demo cart persistence only) |
| **Version Control** | Git + GitHub |

No build tools, no framework, no dependencies — just open the HTML and it works.

---

## How to Run Locally

### Method 1 — Quick preview
```bash
# Clone the repository
git clone https://github.com/da4067258-rgb/sufi-sweets-awp.git
cd sufi-sweets-awp
# Open in your browser
# Windows:
start index.html
# macOS:
open index.html
# Linux:
xdg-open index.html