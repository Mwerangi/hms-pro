# Inventory Module - Migration Schemas

Copy and paste the schema into each migration file's `up()` method.

## 1. Item Categories

```php
Schema::create('item_categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->foreignId('parent_id')->nullable()->constrained('item_categories')->onDelete('set null');
    $table->boolean('is_medicine')->default(false);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

## 2. Stock Locations

```php
Schema::create('stock_locations', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->enum('location_type', ['pharmacy', 'store', 'department', 'ward']);
    $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
    $table->text('description')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

## 3. Suppliers

```php
Schema::create('suppliers', function (Blueprint $table) {
    $table->id();
    $table->string('supplier_code')->unique();
    $table->string('name');
    $table->string('contact_person')->nullable();
    $table->string('phone');
    $table->string('email')->nullable();
    $table->text('address')->nullable();
    $table->string('city')->nullable();
    $table->string('country')->default('Tanzania');
    $table->string('tax_id')->nullable();
    $table->enum('payment_terms', ['cash', 'credit_7', 'credit_15', 'credit_30', 'credit_60'])->default('credit_30');
    $table->decimal('credit_limit', 15, 2)->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

## 4. Inventory Items

```php
Schema::create('inventory_items', function (Blueprint $table) {
    $table->id();
    $table->string('item_code')->unique();
    $table->string('name');
    $table->foreignId('category_id')->constrained('item_categories')->onDelete('restrict');
    $table->text('description')->nullable();
    $table->string('generic_name')->nullable(); // For medicines
    $table->string('unit')->default('pieces'); // tablets, boxes, pieces, bottles, etc
    $table->decimal('unit_price', 10, 2)->default(0);
    $table->integer('reorder_level')->default(10);
    $table->boolean('requires_batch_tracking')->default(false);
    $table->boolean('is_medicine')->default(false);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

## 5. Stock Batches

```php
Schema::create('stock_batches', function (Blueprint $table) {
    $table->id();
    $table->foreignId('item_id')->constrained('inventory_items')->onDelete('restrict');
    $table->string('batch_number');
    $table->date('manufacturing_date')->nullable();
    $table->date('expiry_date')->nullable();
    $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict');
    $table->decimal('cost_price', 10, 2);
    $table->decimal('selling_price', 10, 2);
    $table->integer('quantity_received');
    $table->integer('quantity_remaining')->default(0);
    $table->timestamps();
    
    $table->unique(['item_id', 'batch_number']);
});
```

## 6. Stock

```php
Schema::create('stock', function (Blueprint $table) {
    $table->id();
    $table->foreignId('item_id')->constrained('inventory_items')->onDelete('restrict');
    $table->foreignId('location_id')->constrained('stock_locations')->onDelete('restrict');
    $table->foreignId('batch_id')->nullable()->constrained('stock_batches')->onDelete('set null');
    $table->integer('quantity')->default(0);
    $table->timestamp('last_updated')->useCurrent();
    $table->timestamps();
    
    $table->unique(['item_id', 'location_id', 'batch_id']);
});
```

## 7. Purchase Orders

```php
Schema::create('purchase_orders', function (Blueprint $table) {
    $table->id();
    $table->string('po_number')->unique();
    $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict');
    $table->date('order_date');
    $table->date('expected_delivery')->nullable();
    $table->date('delivery_date')->nullable();
    $table->enum('status', ['draft', 'pending', 'approved', 'received', 'cancelled'])->default('draft');
    $table->decimal('total_amount', 15, 2)->default(0);
    $table->text('notes')->nullable();
    $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
    $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
    $table->timestamp('approved_at')->nullable();
    $table->timestamps();
});
```

## 8. Purchase Order Items

```php
Schema::create('purchase_order_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade');
    $table->foreignId('item_id')->constrained('inventory_items')->onDelete('restrict');
    $table->integer('quantity');
    $table->decimal('unit_price', 10, 2);
    $table->decimal('total', 15, 2);
    $table->integer('received_quantity')->default(0);
    $table->timestamps();
});
```

## 9. Stock Transactions

```php
Schema::create('stock_transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('item_id')->constrained('inventory_items')->onDelete('restrict');
    $table->foreignId('location_id')->constrained('stock_locations')->onDelete('restrict');
    $table->foreignId('batch_id')->nullable()->constrained('stock_batches')->onDelete('set null');
    $table->enum('transaction_type', ['in', 'out', 'transfer', 'adjustment']);
    $table->integer('quantity'); // Positive for IN, negative for OUT
    $table->integer('balance_after'); // Stock level after transaction
    $table->string('reference_type')->nullable(); // PurchaseOrder, Prescription, StockIssue, etc
    $table->unsignedBigInteger('reference_id')->nullable();
    $table->foreignId('from_location_id')->nullable()->constrained('stock_locations')->onDelete('set null');
    $table->foreignId('to_location_id')->nullable()->constrained('stock_locations')->onDelete('set null');
    $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

## 10. Stock Issues

```php
Schema::create('stock_issues', function (Blueprint $table) {
    $table->id();
    $table->string('issue_number')->unique();
    $table->foreignId('location_id')->constrained('stock_locations')->onDelete('restrict');
    $table->string('issued_to'); // Department name or patient name
    $table->enum('issue_type', ['department', 'patient', 'internal'])->default('internal');
    $table->foreignId('issued_by')->constrained('users')->onDelete('restrict');
    $table->date('issue_date');
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

## 11. Stock Issue Items

```php
Schema::create('stock_issue_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('stock_issue_id')->constrained('stock_issues')->onDelete('cascade');
    $table->foreignId('item_id')->constrained('inventory_items')->onDelete('restrict');
    $table->foreignId('batch_id')->nullable()->constrained('stock_batches')->onDelete('set null');
    $table->integer('quantity');
    $table->foreignId('prescription_id')->nullable()->constrained('prescriptions')->onDelete('set null'); // For pharmacy
    $table->timestamps();
});
```

---

## Implementation Order:

1. item_categories
2. stock_locations  
3. suppliers
4. inventory_items
5. stock_batches
6. stock
7. purchase_orders
8. purchase_order_items
9. stock_transactions
10. stock_issues
11. stock_issue_items

After pasting these schemas, run: `php artisan migrate`
