# Product-Supplier Assignment Update

## Summary
Updated the product creation/edit flow to require supplier selection. Products are now assigned to a primary supplier, and purchase orders automatically filter items by the selected supplier.

## Changes Made

### 1. Product Form (`resources/views/admin/inventory/form.blade.php`)
- Added supplier dropdown after category field
- Requires supplier selection when creating/editing products
- Dropdown populated from `$suppliers` variable

### 2. Product Request Validation (`app/Http/Requests/ProductRequest.php`)
- Added `supplier_id` validation rule
- Rule: `'supplier_id' => 'required|exists:suppliers,id'`

### 3. Inventory Controller (`app/Http/Controllers/InventoryController.php`)
- `create()`: Now passes `$suppliers` to view
- `edit()`: Already passes `$suppliers` to view
- `store()`: Saves `supplier_id` when creating products

### 4. Purchase Order Form (`resources/views/admin/purchase-orders/form.blade.php`)
- Existing filtering logic already supports direct supplier assignment via `supplier_id` field
- JavaScript filters products by checking both:
  - Direct assignment: `p.supplier_id == supplierId`
  - Many-to-many: `p.suppliers.some(sp => sp.supplier_id == supplierId)`
- Edit mode items pre-filtered to show only products from selected supplier

## How It Works

1. **Creating a Product**:
   - Admin selects a supplier from the dropdown
   - Product is saved with `supplier_id` in the database
   - Supplier relationship is immediate and required

2. **Creating a Purchase Order**:
   - Admin selects a supplier first
   - "Add Item" button only shows products assigned to that supplier
   - If no products exist for the supplier, user gets an alert
   - Changing supplier updates all item dropdowns to show relevant products

3. **Data Flow**:
   ```
   Product → supplier_id (direct assignment)
   PO Form → allProducts array includes supplier_id
   JS Filter → (p.supplier_id == supplierId) || p.suppliers.includes(supplierId)
   ```

## Database Schema
Products table already has `supplier_id` column (nullable foreign key to suppliers table).

## Benefits
- ✅ Enforces supplier assignment at product creation
- ✅ Prevents adding unrelated products to purchase orders
- ✅ Maintains data integrity between products and suppliers
- ✅ Simplifies PO creation workflow with automatic filtering
- ✅ Supports both direct assignment and many-to-many relationships

## Testing
1. Create/edit a product → Supplier dropdown required
2. Create PO → Select supplier → Add items → Only shows supplier's products
3. Try adding item without selecting supplier → Alert prevents action
4. Change supplier on PO form → Item lists update automatically
