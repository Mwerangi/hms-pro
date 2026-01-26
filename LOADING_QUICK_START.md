## Loading System - Quick Start Guide

### ✅ What's Been Added

The HMS application now has a **complete loading indicator system**:

## 1. **Automatic Page Loading** (Top Progress Bar)
- **Purple animated bar** appears at the top when navigating between pages
- Shows automatically when clicking any link
- No code changes needed - works everywhere!

## 2. **Form Loading Indicators**
All forms in Settings (Branches, Departments) now show:
- **Spinning loader** inside submit buttons
- Button becomes **disabled** during submission
- Prevents **double-clicks** and duplicate submissions

## 3. **Optional Full Page Loader**
For custom operations, you can show a full-screen loading overlay.

---

## How to Use

### For New Forms (Simple Way)

Just add the `form-loading` class to any form:

```html
<form action="/submit" method="POST" class="form-loading">
    @csrf
    <!-- your form fields -->
    <button type="submit">Submit</button>
</form>
```

**That's it!** The loading spinner will automatically show on the submit button.

### For AJAX/Custom Operations

```javascript
// Show loading on a button
showButtonLoading('#myButton');

// Hide loading when done
hideButtonLoading('#myButton');

// Full page loader (for heavy operations)
showPageLoader();
hidePageLoader();
```

---

## What You'll See

### 1. Page Navigation
```
┌─────────────────────────────────────┐
│ ████████ (purple progress bar)      │ ← Top of screen
│                                     │
│  Dashboard Content                  │
└─────────────────────────────────────┘
```

### 2. Button Loading
```
Before Click:
┌──────────────┐
│ Submit Form  │
└──────────────┘

After Click:
┌──────────────┐
│      ⟳      │ ← Spinning loader
└──────────────┘
(Button is disabled)
```

### 3. Full Page Loader (Optional)
```
┌─────────────────────────────────────┐
│                                     │
│           [semi-transparent]        │
│                                     │
│               ⟳                    │ ← Centered spinner
│            Loading...               │
│                                     │
└─────────────────────────────────────┘
```

---

## Where It's Already Working

✅ **Branches Management**
- Create branch form
- Edit branch form
- Delete branch confirmation

✅ **Departments Management**
- Create department form
- Edit department form
- Delete department confirmation

✅ **All Page Navigation**
- Sidebar links
- Breadcrumb links
- Any internal links

✅ **Browser Navigation**
- Back button
- Forward button
- Refresh

---

## Testing It

1. **Go to Settings > Branches**
2. **Click "Add Branch"**
3. **Fill the form and click "Create Branch"**
   - ➡️ You'll see a spinner in the button
   - ➡️ Button becomes disabled
   - ➡️ Progress bar shows at top
4. **Click any sidebar link**
   - ➡️ Purple progress bar animates at top

---

## Customization

All loading elements use HMS purple color (`#667eea`) and work in both light and dark modes.

### Want to change the color?

Edit in `resources/views/layouts/app.blade.php`:

```css
#nprogress .bar {
  background: var(--hms-purple) !important; /* Change this */
}
```

### Want to disable auto-loading on specific forms?

Just don't add the `form-loading` class:

```html
<form action="/submit" method="POST">
  <!-- No automatic loading -->
</form>
```

---

## Examples in Action

### Example 1: Creating a Branch
1. User clicks "Add Branch" button
2. Modal opens
3. User fills form
4. **User clicks "Create Branch"**
   - ✅ Button shows spinner immediately
   - ✅ Top progress bar animates
   - ✅ Button is disabled (prevents double-click)
5. **Form submits to server**
6. **Page redirects** (progress bar completes)
7. **Success toast** shows "Branch created successfully"

### Example 2: Navigating Pages
1. User clicks "Dashboard" in sidebar
2. **Purple progress bar** animates at top
3. Page loads
4. **Progress bar completes** and disappears

---

## Documentation

Full documentation available in `LOADING_SYSTEM.md` including:
- Advanced usage examples
- AJAX integration
- Custom loading handlers
- API reference
- Troubleshooting

---

## Browser Support

Works on all modern browsers:
- Chrome ✅
- Firefox ✅
- Safari ✅
- Edge ✅
- Mobile browsers ✅

---

## Need Help?

Common scenarios covered in `LOADING_SYSTEM.md`:
- AJAX forms
- Modal forms
- Delete actions
- Custom operations
- Error handling
