# Inventory Module Implementation Roadmap

## Overview
Unified hospital inventory system managing medicines, medical equipment, supplies, and consumables with full procurement and stock management capabilities.

---

## Phase 1: Database Foundation (Week 1)

### 1.1 Core Tables Setup
- [ ] Create and run migration: `item_categories`
- [ ] Create and run migration: `stock_locations`
- [ ] Create and run migration: `suppliers`
- [ ] Create and run migration: `inventory_items`

**Testing:**
- [ ] Verify all tables created
- [ ] Check foreign key constraints
- [ ] Seed sample data for testing

### 1.2 Stock Management Tables
- [ ] Create and run migration: `stock_batches`
- [ ] Create and run migration: `stock`

**Testing:**
- [ ] Test batch tracking for medicines
- [ ] Verify stock location relationships

### 1.3 Transaction Tables
- [ ] Create and run migration: `stock_transactions`
- [ ] Create and run migration: `stock_issues`
- [ ] Create and run migration: `stock_issue_items`

**Testing:**
- [ ] Verify transaction logging works
- [ ] Test issue tracking

### 1.4 Procurement Tables
- [ ] Create and run migration: `purchase_orders`
- [ ] Create and run migration: `purchase_order_items`

**Testing:**
- [ ] Test PO creation flow
- [ ] Verify order-item relationships

---

## Phase 2: Models & Relationships (Week 1-2)

### 2.1 Core Models
- [ ] Create model: `ItemCategory` with self-referencing parent relationship
- [ ] Create model: `StockLocation` with department relationship
- [ ] Create model: `Supplier` with validation rules
- [ ] Create model: `InventoryItem` with category, stock, batches relationships

### 2.2 Stock Models
- [ ] Create model: `StockBatch` with item, supplier, stock relationships
- [ ] Create model: `Stock` with item, location, batch relationships
- [ ] Add computed properties: `available_quantity`, `value`

### 2.3 Transaction Models
- [ ] Create model: `StockTransaction` with polymorphic references
- [ ] Create model: `StockIssue` with items relationship
- [ ] Create model: `StockIssueItem` with item, batch relationships

### 2.4 Procurement Models
- [ ] Create model: `PurchaseOrder` with supplier, items, user relationships
- [ ] Create model: `PurchaseOrderItem` with item relationship
- [ ] Add status transitions and approval workflow

**Testing:**
- [ ] Test all model relationships
- [ ] Verify eager loading works
- [ ] Test computed properties

---

## Phase 3: Seeders & Sample Data (Week 2)

### 3.1 Default Categories
- [ ] Medicines (Antibiotics, Painkillers, Vitamins, etc.)
- [ ] Medical Equipment (Surgical, Diagnostic, Monitoring)
- [ ] Laboratory Supplies (Reagents, Consumables)
- [ ] General Supplies (Gloves, Masks, Syringes)

### 3.2 Default Locations
- [ ] Main Pharmacy
- [ ] Emergency Pharmacy
- [ ] Central Store
- [ ] Laboratory Store
- [ ] ICU Store

### 3.3 Sample Data
- [ ] 10 sample suppliers
- [ ] 50+ sample inventory items (medicines + equipment)
- [ ] Initial stock levels
- [ ] Sample batches with expiry dates

---

## Phase 4: Permissions & Roles (Week 2)

### 4.1 Define Permissions
```
inventory.view-dashboard
inventory.manage-categories
inventory.manage-locations
inventory.manage-suppliers
inventory.manage-items
inventory.view-stock
inventory.receive-stock
inventory.issue-stock
inventory.transfer-stock
inventory.adjust-stock
inventory.create-purchase-order
inventory.approve-purchase-order
inventory.view-reports
```

### 4.2 Assign to Roles
- [ ] Admin: All permissions
- [ ] Pharmacist: View, receive, issue (pharmacy locations only)
- [ ] Store Manager: Full stock management
- [ ] Purchase Officer: PO creation
- [ ] Finance Manager: PO approval, reports
- [ ] Doctor/Nurse: View only

**Testing:**
- [ ] Test permission gates
- [ ] Verify role restrictions

---

## Phase 5: Controllers & Business Logic (Week 3)

### 5.1 Basic CRUD Controllers
- [ ] `ItemCategoryController` - manage categories
- [ ] `StockLocationController` - manage locations
- [ ] `SupplierController` - manage suppliers
- [ ] `InventoryItemController` - manage items with search/filter

### 5.2 Stock Management Controllers
- [ ] `StockController` - view current stock levels
- [ ] `StockReceiptController` - receive goods (GRN)
- [ ] `StockIssueController` - dispense items
- [ ] `StockTransferController` - move between locations
- [ ] `StockAdjustmentController` - corrections, damages

### 5.3 Procurement Controllers
- [ ] `PurchaseOrderController` - create, approve, receive POs
- [ ] Add auto-reorder suggestions based on stock levels

### 5.4 Reporting Controllers
- [ ] `InventoryReportController` - stock levels, valuation
- [ ] `ExpiryReportController` - expiring items
- [ ] `MovementReportController` - transaction history
- [ ] `SupplierReportController` - supplier performance

**Testing:**
- [ ] Test all CRUD operations
- [ ] Verify stock calculations
- [ ] Test transaction logging

---

## Phase 6: Routes & Navigation (Week 3)

### 6.1 Route Groups
- [ ] Define inventory route group with middleware
- [ ] Add permission middleware to routes
- [ ] Create resource routes for all controllers

### 6.2 Navigation Menu
- [ ] Add "Inventory" to main sidebar
- [ ] Create inventory sub-menu structure
- [ ] Add breadcrumbs for all pages

---

## Phase 7: Views & UI (Week 4-5)

### 7.1 Inventory Dashboard
- [ ] Stock value summary cards
- [ ] Low stock alerts
- [ ] Expiry alerts (30/60/90 days)
- [ ] Recent transactions
- [ ] Quick actions (issue, receive, transfer)

### 7.2 Master Data Views
- [ ] Item categories (tree view with hierarchy)
- [ ] Stock locations (list with department info)
- [ ] Suppliers (list with contact info)
- [ ] Items (searchable table with category filter)

### 7.3 Stock Management Views
- [ ] Current stock (filterable by location, category)
- [ ] Stock receipt form (GRN with batch entry)
- [ ] Stock issue form (with batch selection - FEFO)
- [ ] Stock transfer form (between locations)
- [ ] Stock adjustment form

### 7.4 Procurement Views
- [ ] Purchase order list (with status filters)
- [ ] Create PO form (item selection, quantities)
- [ ] PO approval view
- [ ] Receive PO (convert to stock receipt)

### 7.5 Reports Views
- [ ] Stock level report (export to Excel)
- [ ] Expiry report (grouped by date ranges)
- [ ] Movement report (date range, filters)
- [ ] Stock valuation report
- [ ] Supplier performance report

**Design:**
- [ ] Use minimal black/gray theme (matching OPD/IPD)
- [ ] Consistent card layouts
- [ ] Quick action buttons
- [ ] Export capabilities

---

## Phase 8: Integration with Pharmacy (Week 5-6)

### 8.1 Medicine Linking
- [ ] Map existing `medicines` table to `inventory_items`
- [ ] Create migration to add `inventory_item_id` to medicines
- [ ] Sync existing medicines to inventory

### 8.2 Prescription Integration
- [ ] Modify prescription dispensing to use inventory stock
- [ ] Auto-create stock issues when dispensing
- [ ] Update stock levels on dispensing
- [ ] Track batch numbers in prescriptions

### 8.3 Stock Deduction
- [ ] Implement FEFO (First Expiry First Out) logic
- [ ] Automatic batch selection on dispensing
- [ ] Transaction logging for all dispensing

**Testing:**
- [ ] Test medicine dispensing flow
- [ ] Verify stock deductions
- [ ] Check transaction audit trail

---

## Phase 9: Advanced Features (Week 6-7)

### 9.1 Automated Alerts
- [ ] Low stock email notifications
- [ ] Expiry alerts (scheduled job)
- [ ] Reorder suggestions
- [ ] Stock variance alerts

### 9.2 Barcode/QR Integration
- [ ] Generate barcodes for items
- [ ] Barcode scanning for stock receipt
- [ ] Barcode scanning for dispensing

### 9.3 Batch Management
- [ ] Batch expiry tracking dashboard
- [ ] Batch history view
- [ ] Return to supplier workflow

### 9.4 Multi-Currency Support
- [ ] Support for purchase in different currencies
- [ ] Exchange rate management
- [ ] Cost calculation in base currency (TSH)

---

## Phase 10: Reporting & Analytics (Week 7-8)

### 10.1 Dashboard Analytics
- [ ] Stock turnover ratio
- [ ] Inventory value trends
- [ ] Department-wise consumption
- [ ] Top 10 items by value/quantity

### 10.2 Financial Reports
- [ ] Purchase summary (monthly/quarterly)
- [ ] Stock valuation (FIFO method)
- [ ] Supplier payment dues
- [ ] Cost analysis reports

### 10.3 Operational Reports
- [ ] ABC analysis (items by value)
- [ ] Fast-moving vs slow-moving items
- [ ] Dead stock report
- [ ] Consumption patterns

---

## Phase 11: Testing & Quality Assurance (Week 8)

### 11.1 Unit Tests
- [ ] Model relationship tests
- [ ] Stock calculation tests
- [ ] Transaction logic tests

### 11.2 Feature Tests
- [ ] Stock receipt workflow
- [ ] Stock issue workflow
- [ ] PO approval workflow
- [ ] Batch expiry logic

### 11.3 Integration Tests
- [ ] Pharmacy integration
- [ ] Permission system
- [ ] Multi-user scenarios

---

## Phase 12: Documentation & Training (Week 9)

### 12.1 User Documentation
- [ ] How to receive stock
- [ ] How to issue stock
- [ ] How to create purchase orders
- [ ] How to run reports

### 12.2 Admin Documentation
- [ ] System setup guide
- [ ] Permission configuration
- [ ] Backup procedures
- [ ] Troubleshooting guide

### 12.3 API Documentation
- [ ] Endpoint documentation
- [ ] Integration guides

---

## Phase 13: Deployment & Go-Live (Week 9-10)

### 13.1 Pre-Deployment
- [ ] Data migration from old system
- [ ] User acceptance testing
- [ ] Performance optimization
- [ ] Security audit

### 13.2 Go-Live
- [ ] Deploy to production
- [ ] User training sessions
- [ ] Monitor system performance
- [ ] Bug fixes and support

---

## Success Metrics

- [ ] 100% stock accuracy
- [ ] Zero stock-outs for critical items
- [ ] 95% on-time PO delivery
- [ ] Reduction in expired medicines by 50%
- [ ] Average stock issue time < 5 minutes
- [ ] User satisfaction > 90%

---

## Current Status: Phase 1 - Database Foundation

**Next Steps:**
1. Implement migration schemas
2. Run migrations
3. Create models
4. Seed sample data
5. Build first UI screens (categories, locations, suppliers)

**Target Completion:** 10 weeks from start date
