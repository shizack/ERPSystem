# AI Features - Enable/Disable Instructions

## Current Status: DISABLED ❌
AI predictions are currently disabled to improve performance on slower network connections (mobile data).

---

## What's Disabled?
1. **AI Stock Predictions** - Forecast charts showing predicted stock levels
2. **AI Risk Levels** - High/Medium/Low risk indicators for stock-outs

---

## How to RE-ENABLE AI Features

### Step 1: Open the Controller File
Navigate to: `app/Http/Controllers/Admin/DashboardController.php`

### Step 2: Enable AI Predictions (Around Line 18-27)

**Find this section:**
```php
// ==================================================================================
// AI PREDICTIONS - TEMPORARILY DISABLED FOR MOBILE DATA PERFORMANCE
// ==================================================================================
// TO RE-ENABLE: Remove the "//" from lines below and comment out the empty collection line
// 
// $aiPredictions = $products->mapWithKeys(function($product) use ($aiService) {
//     return [$product->product_id => $aiService->predictStockOutRisk($product)];
// });

$aiPredictions = collect(); // Empty collection - REMOVE THIS LINE to re-enable AI
// ==================================================================================
```

**Change it to:**
```php
// ==================================================================================
// AI PREDICTIONS - ENABLED
// ==================================================================================

$aiPredictions = $products->mapWithKeys(function($product) use ($aiService) {
    return [$product->product_id => $aiService->predictStockOutRisk($product)];
});

// $aiPredictions = collect(); // Empty collection - COMMENTED OUT
// ==================================================================================
```

### Step 3: Enable AI Risk Count (Around Line 110-117)

**Find this section:**
```php
// ==================================================================================
// AI RISK COUNT - TEMPORARILY DISABLED FOR MOBILE DATA PERFORMANCE
// ==================================================================================
// TO RE-ENABLE: Uncomment the line below and remove the "0" line
//
// $highRiskCount = collect($aiPredictions)->where('risk_level', 'high')->count();

$highRiskCount = 0; // REMOVE THIS LINE to re-enable AI risk count
// ==================================================================================
```

**Change it to:**
```php
// ==================================================================================
// AI RISK COUNT - ENABLED
// ==================================================================================

$highRiskCount = collect($aiPredictions)->where('risk_level', 'high')->count();

// $highRiskCount = 0; // COMMENTED OUT
// ==================================================================================
```

### Step 4: Clear Cache
Run this command in terminal:
```bash
php artisan optimize:clear
```

---

## Important Notes

⚠️ **Performance Warning:**
- AI predictions process ALL products in your inventory
- On slow connections (mobile data), this may cause timeouts
- Recommended to only enable when on stable WiFi connection

✅ **What Works Without AI:**
- All inventory management
- Stock tracking
- Purchase orders
- Requisitions
- Low stock alerts (based on threshold)
- All other system features

---

## Troubleshooting

**If you get 504 timeout after enabling:**
1. Follow the disable steps (reverse of enable)
2. Clear cache: `php artisan optimize:clear`
3. Switch to WiFi connection before enabling again

**If predictions are slow:**
- Consider reducing the number of products
- Check your database connection speed
- Ensure you're on a stable network

---

Last Updated: December 2, 2025
