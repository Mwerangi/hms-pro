# HMS Theme & Design System Guide

**Based on**: Landify Bootstrap Template (Modified for MicroFinance)  
**Design Philosophy**: Minimal, Black & White, Modern, Smooth & Clean

---

## 🎨 **Color Palette**

### Primary Colors
```css
--primary-color: #00a19e       /* Teal - Main brand color */
--primary-dark: #008582        /* Darker teal for hover states */
--secondary-color: #193838     /* Dark teal - Headers & emphasis */
```

### Grayscale (Black & White Focus)
```css
--white: #ffffff               /* Pure white backgrounds */
--light-bg: #f8f9fa           /* Subtle gray backgrounds */
--background: #f7f8fc         /* Dashboard background */
--text-dark: #212529          /* Primary text */
--text-muted: #6c757d         /* Secondary text */
--border-color: #dee2e6       /* Borders & dividers */
```

### Status Colors (Minimal Use)
```css
--success-color: #28a745      /* Success states */
--danger-color: #dc3545       /* Errors & alerts */
--warning-color: #ffc107      /* Warnings */
--info-color: #17a2b8         /* Information */
```

### Accent Colors (Dashboard Enhanced)
```css
--accent-purple: #667eea      /* Modern purple gradient start */
--accent-purple-dark: #764ba2 /* Gradient end */
--accent-green: #48bb78       /* Success/positive metrics */
--accent-orange: #ed8936      /* Warnings/attention */
```

---

## 📐 **Layout Structure**

### Dashboard Layout
```
┌─────────────────────────────────────────────┐
│  Header (60px) - Fixed Top                  │
├──────────┬──────────────────────────────────┤
│          │                                   │
│ Sidebar  │  Main Content Area                │
│ (220px)  │  - Page Title                     │
│ Fixed    │  - Breadcrumb                     │
│          │  - Cards & Content                │
│          │                                   │
│          │                                   │
└──────────┴──────────────────────────────────┘
```

### Responsive Breakpoints
- **Desktop**: ≥ 1200px (Sidebar visible)
- **Tablet**: 768px - 1199px (Sidebar toggleable)
- **Mobile**: < 768px (Sidebar hidden by default)

---

## 🔤 **Typography**

### Font Families
```css
--default-font: "Roboto"       /* Body text */
--heading-font: "Ubuntu"       /* Headings */
--nav-font: "Rubik"           /* Navigation */
```

### Font Sizes
```css
h1: 26-32px    /* Page titles */
h2: 24-28px    /* Section headers */
h3: 20-24px    /* Subsection headers */
h4: 18-20px    /* Card headers */
Body: 14-16px  /* Default text */
Small: 12-14px /* Metadata, labels */
```

### Font Weights
- **Regular**: 400 (Body text)
- **Medium**: 500 (Subheadings, labels)
- **Semi-bold**: 600 (Active states, important text)
- **Bold**: 700 (Headings, emphasis)

---

## 🎯 **Component Styles**

### 1. Sidebar Navigation

**Appearance**: Clean, minimal, modern
```css
/* Features */
- White background (#ffffff)
- 220px width
- Smooth hover effects with purple accent (#667eea)
- Active state with gradient left border
- Collapsible submenu support
- Subtle shadows for depth
```

**Behavior**:
- Active link: Purple text (#667eea) + light purple bg (#f0f4ff)
- Hover: Translate right 2px, purple tint
- Icons: 18px, smooth scale on hover
- Submenu: Indented 48px from left

### 2. Header/Navbar

**Appearance**: Glass morphism effect
```css
/* Styling */
- Height: 60px fixed
- Background: rgba(255, 255, 255, 0.95)
- Backdrop blur: 20px
- Subtle bottom border
- Notifications with pulse animation
- Profile dropdown
```

### 3. Cards

**Info Cards** (Dashboard metrics):
```css
/* Design */
- White background
- Top gradient border (3px)
- Rounded corners: 12px
- Subtle shadow: 0 2px 8px rgba(0,0,0,0.06)
- Icon: 64x64px, gradient background
- Number: 30px, bold, dark text
```

**Content Cards**:
```css
/* Standard card */
- Padding: 20-24px
- Border radius: 12px
- Box shadow: Soft, subtle
- Border: Optional 1px solid #e2e8f0
```

### 4. Buttons

**Primary Button**:
```css
background: #00a19e (Teal)
padding: 14px 28px
border-radius: 8px
font-weight: 600
transition: all 0.3s ease

/* Hover */
background: #008582 (Darker teal)
transform: translateY(-2px)
box-shadow: Medium shadow
```

**Secondary Button**:
```css
background: #f8f9fa (Light gray)
color: #212529 (Dark text)
border: 1px solid #dee2e6
```

**Gradient Button** (Modern variant):
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)
color: #ffffff
```

### 5. Forms

**Input Fields**:
```css
/* Style */
- Height: 50px
- Border: 2px solid #dee2e6
- Border radius: 8px
- Padding-left: 48px (with icon)

/* Focus state */
- Border: 2px solid #00a19e
- Box shadow: 0 0 0 0.2rem rgba(0, 161, 158, 0.15)
```

**Labels**:
```css
font-weight: 500
color: #212529
margin-bottom: 8px
```

**Icon in Input**:
```css
position: absolute
left: 16px
font-size: 18px
color: #6c757d
```

### 6. Tables

**Modern Table Style**:
```css
/* Header */
background: #f8f9fa
font-weight: 600
border-bottom: 2px solid #dee2e6

/* Rows */
border-bottom: 1px solid #e2e8f0
hover: background #f7f8fc

/* Alternating rows (optional) */
nth-child(even): background #fafbfc
```

### 7. Badges & Pills

```css
/* Status badge */
padding: 4px 12px
border-radius: 12px
font-size: 11px
font-weight: 600
text-transform: uppercase

/* Colors */
success: #28a745
warning: #ffc107
danger: #dc3545
info: #17a2b8
```

---

## 🎭 **Design Patterns**

### Shadows (Elevation System)
```css
--shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.08)     /* Subtle */
--shadow-md: 0 4px 12px rgba(0, 0, 0, 0.12)    /* Medium */
--shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15)    /* Strong */
```

### Border Radius
```css
Small: 6px    /* Badges, small elements */
Medium: 8px   /* Buttons, inputs */
Large: 12px   /* Cards, containers */
XL: 16px      /* Modals, large containers */
```

### Transitions
```css
--transition: all 0.3s ease
--transition-fast: all 0.2s ease
--transition-slow: all 0.5s cubic-bezier(0.4, 0, 0.2, 1)
```

### Gradients

**Primary Gradient**:
```css
linear-gradient(135deg, #00a19e 0%, #193838 100%)
/* Use for: Login backgrounds, hero sections */
```

**Accent Gradient** (Modern):
```css
linear-gradient(135deg, #667eea 0%, #764ba2 100%)
/* Use for: Active states, highlights, CTAs */
```

**Success Gradient**:
```css
linear-gradient(135deg, #48bb78 0%, #38a169 100%)
```

---

## 📱 **Login/Authentication Pages**

### Layout
- **Split Screen**: 
  - Left: Form (40-50% width)
  - Right: Visual/Illustration (50-60% width)
- **Background**: Full gradient overlay
- **Card**: White, rounded 16px, shadow-lg

### Form Section
```css
/* Features */
- Centered logo with icon
- Large heading (32px)
- Descriptive subtext
- Icon-based inputs
- Password toggle visibility
- Remember me checkbox
- Forgot password link
- Demo credentials box (light gray bg)
- Register/Login link at bottom
```

### Visual Section
```css
/* Features */
- Gradient overlay on image
- Large heading (36px, white)
- Feature list with icons
- Stats preview (optional)
- Benefits showcase
- Glassmorphism effect on cards
```

---

## 🎨 **Dashboard Components**

### Stat Cards (Top Metrics)
```css
/* Layout */
- Grid: 3-4 columns on desktop
- Icon: Left side, gradient background
- Number: Large (30px), bold
- Label: Small text below
- Trend indicator: Optional (↑ ↓)
- Top colored border (3px gradient)
```

### Charts & Graphs
```css
/* Container */
- White card background
- Padding: 24px
- Title: Bold, 18px
- Filters: Top right
- Chart area: Full width
```

### Recent Activity / Lists
```css
/* Style */
- White background
- Items separated by 1px border
- Hover: Light gray background
- Avatar/Icon: Left aligned
- Timestamp: Right aligned, muted
```

---

## 🔧 **Interactive Elements**

### Hover Effects
```css
/* Links */
color: #00a19e on hover
transform: slight upward movement

/* Cards */
transform: translateY(-4px)
box-shadow: Increased

/* Buttons */
transform: translateY(-2px)
brightness: 1.1
```

### Active States
```css
/* Navigation */
- Purple accent (#667eea)
- Light purple background
- Left gradient border
- Icon scale 1.05

/* Form inputs */
- Primary color border
- Subtle colored shadow
```

### Loading States
```css
/* Skeleton loaders */
- Gray placeholder
- Shimmer animation
- Maintains layout

/* Spinners */
- Primary color (#00a19e)
- Smooth rotation
- Center aligned
```

---

## 📋 **Page Templates**

### 1. Login Page
- Gradient background
- Split layout (form + visual)
- Clean white form card
- Demo credentials
- Smooth animations

### 2. Dashboard Home
- Top stat cards (4 columns)
- Chart section (revenue/activity)
- Recent items table
- Quick actions sidebar

### 3. List/Table Page
- Page title + breadcrumb
- Search & filter bar
- Action buttons (top right)
- Data table with pagination
- Status badges

### 4. Form Page
- Page title + breadcrumb
- White card container
- Sectioned form fields
- Submit buttons (bottom)
- Validation messages

### 5. Details/Profile Page
- Header section (title + actions)
- Sidebar (info card)
- Main content (tabs)
- Activity timeline (optional)

---

## 🎯 **HMS-Specific Adaptations**

### Color Mapping for HMS

**Replace MicroFinance branding with HMS**:
```css
Logo: Hospital icon (bi-hospital / bi-heart-pulse)
Primary: Keep #00a19e (Professional healthcare feel)
Secondary: #193838 (Trust & stability)
Accent: #667eea (Modern, tech-forward)
```

### Icon Recommendations (Bootstrap Icons)

**Dashboard**:
- `bi-grid` - Dashboard home
- `bi-hospital` - HMS logo/branding
- `bi-person-badge` - Doctors
- `bi-people` - Patients
- `bi-calendar-check` - Appointments
- `bi-clipboard-pulse` - Medical records
- `bi-capsule` - Pharmacy
- `bi-heart-pulse-fill` - Emergency/ER
- `bi-activity` - Patient vitals
- `bi-file-earmark-medical` - Lab reports

**Actions**:
- `bi-plus-circle` - Add new
- `bi-pencil-square` - Edit
- `bi-trash` - Delete
- `bi-eye` - View details
- `bi-printer` - Print
- `bi-download` - Download
- `bi-bell` - Notifications

### Status Color Usage

**Patient Status**:
- Active/Admitted: `#48bb78` (Green)
- Discharged: `#718096` (Gray)
- Critical: `#dc3545` (Red)
- Observation: `#ffc107` (Yellow)

**Appointment Status**:
- Confirmed: `#48bb78` (Green)
- Pending: `#ffc107` (Yellow)
- Cancelled: `#dc3545` (Red)
- Completed: `#718096` (Gray)

**Payment Status**:
- Paid: `#48bb78` (Green)
- Pending: `#ffc107` (Yellow)
- Overdue: `#dc3545` (Red)
- Partial: `#17a2b8` (Blue)

---

## 🎬 **Animations & Transitions**

### Page Transitions
```css
/* Fade in on load */
opacity: 0 → 1
duration: 0.3s

/* Slide in cards */
transform: translateY(20px) → translateY(0)
duration: 0.4s
stagger: 0.1s per card

/* Stat cards staggered animation */
animation: fadeInUp 0.6s ease backwards
delay: 0.1s, 0.2s, 0.3s, 0.4s per card
```

### Micro-interactions
```css
/* Button click */
transform: scale(0.95)
duration: 0.1s

/* Button hover with ripple effect */
transform: translateY(-2px)
box-shadow expansion
background circle expand from center

/* Card hover elevation */
transform: translateY(-4px) to translateY(-8px)
box-shadow: 0 4px 12px → 0 16px 32px
duration: 0.4s cubic-bezier(0.4, 0, 0.2, 1)

/* Icon hover rotation */
transform: rotate(15deg)
duration: 0.3s

/* Notification appear */
slide from top + fade in
duration: 0.3s

/* Modal open */
backdrop fade in + scale up
duration: 0.3s
```

### Toast Notifications
```css
/* Welcome Toast */
slide in from right: translateX(400px) → translateX(0)
duration: 0.5s cubic-bezier(0.4, 0, 0.2, 1)
auto-dismiss: 6 seconds
slide out: translateX(0) → translateX(400px)
duration: 0.4s

/* Standard Toast */
slide in from right
duration: 0.3s
auto-dismiss: 5 seconds
manual close available
```

### Loading Animations
```css
/* Spinner */
rotate 360deg infinite
duration: 1s linear

/* Progress bar */
width: 0 → 100%
transition: width 0.3s ease

/* Skeleton shimmer */
background position animation
duration: 1.5s infinite
gradient: #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%
```

---

## 🆕 **Modern UI Features (Phase 1 Enhancement)**

### 1. Statistics Cards
**Purpose**: Display key metrics with visual appeal

**Design Specs**:
```css
Layout: Grid (auto-fit, minmax(240px, 1fr))
Background: white
Border-radius: 16px
Padding: 28px
Border: 1px solid #e2e8f0

/* Hover Effect */
transform: translateY(-8px)
box-shadow: 0 16px 32px rgba(0, 0, 0, 0.12)
top-border gradient bar: 4px height, scales from 0 to 100%

/* Icon Container */
Size: 56x56px
Border-radius: 14px
Background: linear-gradient(135deg, color-start, color-end)
Box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15)
Hover: scale(1.1) rotate(5deg)

/* Value Display */
Font-size: 36px
Font-weight: 700
Color: #2d3748

/* Trend Indicator */
Background: rgba(color, 0.1)
Border-radius: 8px
Padding: 6px 12px
```

**Color Schemes**:
- Primary: `#667eea` → `#764ba2` (Purple gradient)
- Success: `#48bb78` → `#38a169` (Green gradient)
- Warning: `#ed8936` → `#dd6b20` (Orange gradient)
- Info: `#4299e1` → `#3182ce` (Blue gradient)

### 2. Dark Mode Support
**Purpose**: Reduce eye strain, modern UX expectation

**Implementation**:
```css
/* Theme Toggle */
data-theme="light" (default)
data-theme="dark"

/* CSS Variables */
Light Mode:
--bg-primary: #ffffff
--bg-secondary: #f7f8fc
--text-primary: #2d3748
--text-secondary: #718096
--border-color: #e2e8f0

Dark Mode:
--bg-primary: #1a202c
--bg-secondary: #2d3748
--text-primary: #f7fafc
--text-secondary: #cbd5e0
--border-color: #4a5568

/* Transition */
All color properties: transition 0.3s ease
Stored in localStorage for persistence
Icon change: moon ↔ sun
```

### 3. Breadcrumb Navigation
**Purpose**: Orientation and quick navigation

**Design**:
```css
Background: var(--bg-primary)
Border-bottom: 1px solid var(--border-color)
Padding: 4px 20px (compact)
Font-size: 14px

/* Links */
Color: var(--hms-purple)
Hover: var(--hms-purple-dark)
Active: var(--text-secondary)
Home icon included
```

### 4. Sortable Tables
**Purpose**: User-friendly data organization

**Features**:
- Click column headers to sort
- Visual indicators (↑ ↓)
- Hover effect on headers
- Maintains selection state
- Smooth row reordering

**Styling**:
```css
/* Header Hover */
background: rgba(102, 126, 234, 0.05)

/* Sort Icons */
Unsorted: opacity 0.3
Ascending: ↑ (opacity 1, purple)
Descending: ↓ (opacity 1, purple)

/* Row Hover */
background: rgba(102, 126, 234, 0.03)
transform: scale(1.001)
```

### 5. Bulk Actions
**Purpose**: Efficient multi-item operations

**Components**:
```css
/* Selection Bar */
Background: rgba(102, 126, 234, 0.05)
Padding: 12px 20px
Border-radius: 12px
Animation: slideDown 0.3s ease

/* Checkboxes */
Row-level selection
Select all functionality
Selected count display

/* Action Buttons */
Activate, Deactivate, Delete
Color-coded (success, secondary, danger)
Confirmation dialogs
```

### 6. Export Functionality
**Purpose**: Data portability

**UI Elements**:
```css
/* Export Button */
background: linear-gradient(135deg, #48bb78, #38a169)
Hover: translateY(-2px) + enhanced shadow

/* Export Modal */
Fixed center position
White background, rounded corners
Options: Excel, PDF
Backdrop overlay
```

### 7. Enhanced Search
**Purpose**: Quick data filtering

**Features**:
- Debounced input (500ms delay)
- Loading placeholder feedback
- Ready for autocomplete
- Focus effects with transform

### 8. Action Button Enhancements
**Purpose**: Better visibility and interaction

**Specifications**:
```css
Size: 40x40px (increased from 32px)
Gap between icons: 2px (reduced from 8px)
Font-size: 16px
Border-radius: 8px

/* Hover Effects */
transform: translateY(-2px)
box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15)
Ripple effect from center
Color-specific backgrounds on hover
```

### 9. Form Enhancements
**Purpose**: Improved user input experience

**Features**:
```css
/* Focus States */
border-color: var(--hms-purple)
box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25)
transform: translateY(-1px)

/* Submit Buttons */
Loading spinner on submit
Button disabled state
Text change to "Loading..."
```

### 10. Welcome Toast
**Purpose**: Personalized user greeting

**Specs**:
```css
/* Container */
Position: fixed top-right
Width: 380px
Background: linear-gradient(135deg, #667eea, #764ba2)
Border-radius: 16px
Padding: 24px 28px
Box-shadow: 0 12px 40px rgba(102, 126, 234, 0.4)

/* Animation */
Slide in: 0.5s cubic-bezier
Auto-dismiss: 6 seconds
Manual close available

/* Content */
Emoji icon: 👋 (56x56px container)
Personalized greeting
Current date display
```

---

## 📦 **Assets Structure**

```
theme/
├── assets/
│   ├── css/
│   │   ├── main.css              # Base styles
│   │   ├── dashboard.css         # Dashboard-specific
│   │   └── microfinance.css      # Custom components
│   ├── js/
│   │   └── main.js              # JavaScript utilities
│   ├── img/
│   │   └── (icons, illustrations)
│   └── vendor/
│       ├── bootstrap/           # Bootstrap framework
│       ├── bootstrap-icons/     # Icon library
│       └── aos/                 # Animation library
```

---

## ✅ **Design Checklist for HMS**

When creating new pages/components:

- [ ] Use white or very light gray backgrounds
- [ ] Primary text: Dark (#212529), secondary: Muted (#6c757d)
- [ ] Accent color: Teal (#00a19e) or Purple (#667eea)
- [ ] Border radius: 8-12px for consistency
- [ ] Shadows: Subtle (shadow-sm or shadow-md)
- [ ] Transitions: 0.3s ease on interactive elements
- [ ] Icons: Bootstrap Icons, 18-20px for UI
- [ ] Spacing: 16-24px padding in cards
- [ ] Typography: Roboto body, Ubuntu headings
- [ ] Hover states: Color change + slight transform
- [ ] Responsive: Mobile-first, test at 768px/1200px
- [ ] Accessibility: ARIA labels, keyboard navigation

---

## 🎨 **Theme Philosophy Summary**

### Core Principles:
1. **Minimalism**: Clean, uncluttered interfaces
2. **Black & White Focus**: Grayscale base with accent colors sparingly
3. **Modern**: Gradients, glassmorphism, smooth animations
4. **Professional**: Healthcare-appropriate, trustworthy design
5. **User-Friendly**: Clear hierarchy, intuitive navigation
6. **Responsive**: Mobile-ready, fluid layouts
7. **Consistent**: Reusable patterns, design system

### Visual Language:
- **Light**: Predominantly white/light backgrounds
- **Depth**: Subtle shadows, layering
- **Clean**: Sharp borders, clear separation
- **Smooth**: Gentle transitions, no jarring movements
- **Modern**: Contemporary gradients, glassmorphism
- **Accessible**: High contrast, readable fonts

---

**Last Updated**: January 20, 2026  
**Version**: 2.0 - Modern UI Enhancement Phase Complete  
**Next Step**: Implement Patient Management Module with established design system

---

## 📋 **Feature Implementation Checklist**

### Completed ✅
- [x] Statistics cards with animations
- [x] Toast notification system
- [x] Dark mode toggle with persistence
- [x] Breadcrumb navigation
- [x] Sortable table columns
- [x] Bulk selection and actions
- [x] Export functionality (UI ready)
- [x] Enhanced search with debouncing
- [x] Action button improvements
- [x] Form focus enhancements
- [x] Welcome toast notification
- [x] Smooth page transitions
- [x] Hover effects and micro-interactions
- [x] Compact spacing optimization

### Pending 🔄
- [ ] Export backend implementation (Excel/PDF)
- [ ] Autocomplete search functionality
- [ ] Real-time notifications
- [ ] Profile dropdown menu
- [ ] Advanced filtering options
- [ ] Data visualization charts
- [ ] Mobile responsive refinements
