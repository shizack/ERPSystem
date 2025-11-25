<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Google Icons and Font Awesome -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: #ecf0f1;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            transition: all 0.3s;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px;
            background: #1a252f;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-header h3 {
            color: #fff;
            margin: 0;
            font-size: 1.2rem;
        }

        .sidebar-menu {
            padding: 15px 0;
        }

        .sidebar-menu h3 {
            color: #7f8c8d;
            font-size: 12px;
            text-transform: uppercase;
            padding: 0 20px 10px;
            margin-bottom: 5px;
            border-bottom: 1px solid #34495e;
        }

        .sidebar-menu ul {
            list-style: none;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li.active a {
            background: #34495e;
            color: #fff;
        }

        .sidebar-menu li a i {
            margin-right: 10px;
            font-size: 20px;
            width: 20px;
            text-align: center;
        }

        .sidebar-menu .submenu {
            padding-left: 20px;
            display: none;
        }

        .sidebar-menu .submenu.show {
            display: block;
        }

        .sidebar-menu .has-submenu > a:after {
            content: 'expand_more';
            font-family: 'Material Icons';
            margin-left: auto;
            transition: transform 0.3s;
        }

        .sidebar-menu .has-submenu.active > a:after {
            transform: rotate(180deg);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            transition: margin 0.3s;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info img {
            border-radius: 50%;
        }

        /* Summary Cards */
        .summary-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-left: 4px solid #28a745;
        }

        /* AI Predictions Grid */
        .ai-predictions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .ai-prediction-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
            transition: transform 0.2s;
        }

        .ai-prediction-card:hover {
            transform: translateY(-5px);
        }

        /* Risk Level Badges */
        .risk-high { --risk-bg: #ffebee; --risk-text: #c62828; }
        .risk-medium { --risk-bg: #fff8e1; --risk-text: #ff8f00; }
        .risk-low { --risk-bg: #e8f5e9; --risk-text: #2e7d32; }

        /* Animations */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .spin {
            animation: spin 1s linear infinite;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                left: -250px;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.active {
                margin-left: 250px;
            }
            
            .summary-row {
                grid-template-columns: 1fr 1fr;
            }
            
            .ai-predictions-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .summary-row {
                grid-template-columns: 1fr;
            }
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            background: #2c3e50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
            z-index: 1001;
            cursor: pointer;
        }

        @media (max-width: 992px) {
            .mobile-menu-btn {
                display: block;
            }
        }

        /* Breadcrumbs */
        .breadcrumbs {
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        .breadcrumbs a {
            color: #007bff;
            text-decoration: none;
        }

        .breadcrumbs a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>ERP System</h3>
        </div>
        
        <div class="sidebar-menu">
            <h3>Main</h3>
            <ul>
                <li class="active">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="material-icons">dashboard</i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.inventory.index') }}">
                        <i class="material-icons">inventory</i>
                        <span>Inventory</span>
                    </a>
                </li>
                <li class="has-submenu">
                    <a href="#">
                        <i class="material-icons">shopping_cart</i>
                        <span>Orders</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="#">All Orders</a></li>
                        <li><a href="#">Create Order</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="material-icons">people</i>
                        <span>Customers</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="material-icons">local_shipping</i>
                        <span>Suppliers</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="material-icons">assessment</i>
                        <span>Reports</span>
                    </a>
                </li>
            </ul>
            
            <h3>System</h3>
            <ul>
                <li>
                    <a href="#">
                        <i class="material-icons">settings</i>
                        <span>Settings</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="material-icons">logout</i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()">
        <i class="material-icons">menu</i>
    </button>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <div class="header">
            <h1 style="font-size: 24px;">Dashboard</h1>
            <div class="user-info">
                <img src="https://placehold.co/40x40/007bff/ffffff?text=AD" alt="Admin Profile">
                <div>
                    <div style="font-weight: bold;">{{ Auth::user()->full_name }}</div>
                    <div style="font-size: 13px; color: #888;">Administrator</div>
                </div>
            </div>
        </div>

        <!-- Breadcrumbs -->
        <div class="breadcrumbs">
            <a href="{{ route('admin.dashboard') }}">Home</a> / Dashboard
        </div>

        <!-- Summary Cards -->
        <div class="summary-row">
            <div class="summary-card" style="border-left-color: #28a745;">
                <h4>Total Inventory Items</h4>
                <p>{{ $inventoryCount ?? 0 }}</p>
            </div>
            <div class="summary-card" style="border-left-color: #ffc107;">
                <h4>High Risk Items</h4>
                <p>{{ $highRiskCount ?? 0 }}</p>
            </div>
            <div class="summary-card" style="border-left-color: #17a2b8;">
                <h4>Low Stock Items</h4>
                <p>{{ $lowStockCount ?? 0 }}</p>
            </div>
            <div class="summary-card" style="border-left-color: #dc3545;">
                <h4>Critical Stock</h4>
                <p>{{ $criticalStockCount ?? 0 }}</p>
            </div>
        </div>

        <!-- AI Inventory Section -->
        <div class="ai-inventory-section" style="margin: 40px 0; background: white; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); padding: 20px;">
            <div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 20px; font-weight: 600; margin: 0;">AI Inventory Predictions</h2>
                <button class="refresh-btn" onclick="refreshAIData()" 
                        style="background: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                    <i class="material-icons" id="refreshIcon">refresh</i> Refresh
                </button>
            </div>

            @if(isset($aiPredictions) && count($aiPredictions) > 0)
                <div class="ai-predictions-grid">
                    @foreach($aiPredictions as $productId => $prediction)
                        @php
                            $product = \App\Models\Product::with(['supplier', 'category'])->find($productId);
                            $riskClass = strtolower($prediction['risk_level'] ?? 'low');
                        @endphp
                        
                        <div class="ai-prediction-card">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                                <h4 style="margin: 0; font-size: 16px; color: #2c3e50;">{{ $product->name ?? 'Unknown Product' }}</h4>
                                <span class="badge risk-{{ $riskClass }}" 
                                      style="background: var(--risk-bg, #f5f5f5); 
                                             color: var(--risk-text, #666);
                                             padding: 4px 10px; 
                                             border-radius: 12px; 
                                             font-size: 12px; 
                                             font-weight: 500; 
                                             text-transform: capitalize;">
                                    {{ $prediction['risk_level'] ?? 'low' }} Risk
                                </span>
                            </div>
                            
                            <div class="forecast-graph" id="forecast-{{ $productId }}" style="height: 150px; margin-bottom: 15px;">
                                <!-- Chart.js will render here -->
                            </div>
                            
                            <div class="ai-insights" style="font-size: 13px; color: #555; line-height: 1.5;">
                                <p style="margin: 5px 0;"><strong>📈 Forecast:</strong> {{ number_format($prediction['forecasted_usage_30d'] ?? 0) }} units next 30 days</p>
                                <p style="margin: 5px 0;"><strong>🔍 Trend:</strong> {{ $prediction['insights']['trend'] ?? 'No trend data' }}</p>
                                <p style="margin: 5px 0;"><strong>🔄 Pattern:</strong> {{ $prediction['insights']['seasonality'] ?? 'No pattern detected' }}</p>
                                <p style="margin: 5px 0;"><strong>💡 Recommendation:</strong> {{ $prediction['insights']['recommended_action'] ?? 'No recommendation available' }}</p>
                            </div>
                            
                            <div class="confidence" style="margin-top: 15px; font-size: 12px; text-align: right; color: {{ 
                                ($prediction['confidence'] ?? 0) > 0.7 ? '#2e7d32' : 
                                (($prediction['confidence'] ?? 0) > 0.4 ? '#ff8f00' : '#c62828') 
                            }};">
                                <i class="fas fa-chart-line"></i> Forecast Confidence: {{ round(($prediction['confidence'] ?? 0) * 100) }}%
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 40px 20px; color: #666;">
                    <i class="material-icons" style="font-size: 48px; color: #ddd; margin-bottom: 10px;">insights</i>
                    <p>No prediction data available. The system needs more usage data to generate accurate forecasts.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
    // Toggle sidebar for mobile
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        sidebar.classList.toggle('active');
        mainContent.classList.toggle('active');
    }

    // Handle submenu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const submenuToggles = document.querySelectorAll('.has-submenu > a');
        
        submenuToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.parentElement;
                const submenu = this.nextElementSibling;
                
                // Close other open submenus
                document.querySelectorAll('.has-submenu').forEach(item => {
                    if (item !== parent && item.classList.contains('active')) {
                        item.classList.remove('active');
                        item.querySelector('.submenu').classList.remove('show');
                    }
                });
                
                // Toggle current submenu
                parent.classList.toggle('active');
                submenu.classList.toggle('show');
            });
        });

        // Initialize charts
        @if(isset($aiPredictions))
            @foreach($aiPredictions as $productId => $prediction)
                @if(isset($prediction['forecast_data']) && is_array($prediction['forecast_data']))
                    const ctx{{ $productId }} = document.getElementById('forecast-{{ $productId }}').getContext('2d');
                    new Chart(ctx{{ $productId }}, {
                        type: 'line',
                        data: {
                            labels: {!! json_encode(array_column($prediction['forecast_data'], 'ds')) !!},
                            datasets: [{
                                label: 'Forecasted Usage',
                                data: {!! json_encode(array_column($prediction['forecast_data'], 'yhat')) !!},
                                borderColor: 'rgba(54, 162, 235, 1)',
                                tension: 0.1,
                                fill: true,
                                backgroundColor: 'rgba(54, 162, 235, 0.1)'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { display: false } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                @endif
            @endforeach
        @endif
    });

    // Refresh AI Data
    function refreshAIData() {
        const refreshBtn = document.querySelector('.refresh-btn');
        const refreshIcon = document.getElementById('refreshIcon');
        
        refreshBtn.disabled = true;
        refreshIcon.classList.add('spin');
        
        // Reload the page after a short delay to show the loading state
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }
    </script>
</body>
</html>