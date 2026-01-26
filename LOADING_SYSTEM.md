# Loading System Documentation

The HMS application includes a comprehensive loading/progress indicator system to enhance user experience during form submissions and page navigation.

## Features

### 1. **Top Progress Bar (NProgress)** 
- Thin animated progress bar at the top of the page
- Automatically shows during page navigation
- Purple color matching the HMS theme
- Smooth animations

### 2. **Button Loading States**
- Spinner replaces button text during submission
- Button becomes disabled to prevent double-clicks
- Works with any submit button

### 3. **Full Page Loader** (Optional)
- Semi-transparent overlay with centered spinner
- Can be triggered programmatically for heavy operations

## Automatic Features

### Page Navigation Loading
The progress bar **automatically shows** when:
- User clicks any internal link
- Page is loading
- Browser back/forward buttons are used

**No code needed** - works out of the box!

### Form Auto-Loading
Add the `form-loading` class to any form:

```html
<form action="/submit" method="POST" class="form-loading">
    @csrf
    <input type="text" name="name" required>
    <button type="submit">Submit</button>
</form>
```

The button will **automatically** show a loading spinner when submitted.

## Manual Usage

### 1. Manual Form Loading

For forms with custom submit handlers:

```html
<form id="myForm" action="/submit" method="POST">
    @csrf
    <input type="text" name="name" required>
    <button type="submit" id="submitBtn">Submit</button>
</form>

<script>
document.getElementById('myForm').addEventListener('submit', function(e) {
    const button = document.getElementById('submitBtn');
    handleFormSubmit(this, button);
});
</script>
```

### 2. Button Loading Control

Manually show/hide loading on any button:

```html
<button id="myButton" class="btn btn-primary">
    Process Data
</button>

<script>
// Show loading
showButtonLoading('#myButton');

// After operation completes
setTimeout(() => {
    hideButtonLoading('#myButton');
}, 2000);
</script>
```

### 3. Full Page Loader

For heavy operations or AJAX requests:

```javascript
// Show full page loader
showPageLoader();

// Perform your operation
fetch('/api/heavy-operation')
    .then(response => response.json())
    .then(data => {
        // Hide loader when done
        hidePageLoader();
    })
    .catch(error => {
        hidePageLoader();
    });
```

## Examples

### Example 1: Modal Form with Loading

```html
<!-- Create Branch Modal -->
<div class="modal fade" id="createBranchModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('settings.branches.store') }}" method="POST" class="form-loading">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Branch Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Branch</button>
                </div>
            </form>
        </div>
    </div>
</div>
```

### Example 2: AJAX Form with Custom Loading

```html
<form id="ajaxForm">
    <input type="text" name="data" required>
    <button type="submit" id="ajaxSubmit">Submit</button>
</form>

<script>
document.getElementById('ajaxForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const button = document.getElementById('ajaxSubmit');
    const formData = new FormData(this);
    
    // Show button loading
    showButtonLoading(button);
    
    fetch('/api/endpoint', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        hideButtonLoading(button);
        showToast('Success!', 'success');
    })
    .catch(error => {
        hideButtonLoading(button);
        showToast('Error occurred', 'error');
    });
});
</script>
```

### Example 3: Delete Action with Loading

```html
<button type="button" class="btn btn-danger" onclick="deleteItem(123)">
    Delete
</button>

<script>
function deleteItem(id) {
    if (confirm('Are you sure?')) {
        const button = event.target;
        showButtonLoading(button);
        
        fetch(`/api/items/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (response.ok) {
                showToast('Item deleted', 'success');
                location.reload();
            }
        })
        .catch(error => {
            hideButtonLoading(button);
            showToast('Error deleting item', 'error');
        });
    }
}
</script>
```

## Available Functions

### Global Functions

```javascript
// Show loading on a button
showButtonLoading(button)
// button: DOM element or CSS selector string

// Hide loading from a button
hideButtonLoading(button)
// button: DOM element or CSS selector string

// Show full page loader
showPageLoader()

// Hide full page loader
hidePageLoader()

// Handle form submission (used internally)
handleFormSubmit(form, submitButton)
```

## Styling

All loading elements are themed to match HMS colors:
- **Progress bar color:** Purple (`#667eea`)
- **Spinner color:** Purple (`#667eea`)
- **Dark mode:** Automatically adjusts

## NProgress Configuration

The top progress bar is configured with:
```javascript
{
    showSpinner: false,      // No circular spinner (we use bar only)
    trickleSpeed: 200,       // Animation speed
    minimum: 0.08,           // Starting position
    easing: 'ease',          // Animation easing
    speed: 500               // Completion speed
}
```

## Best Practices

1. **Use `form-loading` class** for simple forms - it's automatic!
2. **Show loading immediately** when user takes action
3. **Always hide loading** even on errors
4. **Combine with toast notifications** for better UX
5. **Use full page loader** sparingly - only for heavy operations
6. **Test on slow connections** to see the effect

## Browser Support

- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)
- ✅ Mobile browsers

## Troubleshooting

### Loading doesn't show
- Check if form has `class="form-loading"`
- Verify button type is `type="submit"`
- Check browser console for errors

### Loading doesn't hide
- Make sure to call `hideButtonLoading()` or `hidePageLoader()`
- On page reload, loading auto-hides

### Multiple spinners on one button
- Only call `showButtonLoading()` once per action
- Check for duplicate event listeners

## Integration with Existing Code

The loading system is already integrated into:
- ✅ Settings forms (branches, departments, roles, permissions)
- ✅ Page navigation
- ✅ Modal forms
- ✅ All internal links

Add the `form-loading` class to any new forms to enable automatic loading!
