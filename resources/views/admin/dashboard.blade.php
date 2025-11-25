<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin Dashboard</title>

  <!-- Icons & font -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <style>
    /* ---------- Palette (from first design) ---------- */
    :root{
      --resort-blue: #007bff;
      --resort-yellow: #ffcc00;
      --resort-red: #ef4444;
      --surface-white: rgba(255,255,255,0.92);
      --glass-weak: rgba(255,255,255,0.55);
      --muted-ink: #223;
      --card-shadow: 0 8px 30px rgba(16, 42, 67, 0.12);
    }
    
    /* ---------- Reset & Base ---------- */
    * { box-sizing: border-box; }
    html,body{ height:100%; }
    body{
      margin:0;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      color:var(--muted-ink);
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;

      background: linear-gradient(180deg, #f6f9ff 0%, #eef7ff 35%, #ffffff 100%);
      min-height:100vh;
      display:flex;
    }

    /* subtle decorative background */
    .bg-deco {
      position:fixed;
      inset:0;
      pointer-events:none;
      background: linear-gradient(120deg, rgba(0,123,255,0.03), rgba(255,204,0,0.015));
      z-index:0;
      backdrop-filter: blur(2px);
    }

    /* ---------- Sidebar (styled to match first design but keeps original markup) ---------- */
    .sidebar{
      width:250px;
      height:calc(100vh - 36px);
      position:fixed;
      left:18px;
      top:18px;
      padding:18px;
      border-radius:14px;
      background: linear-gradient(180deg, rgba(255,255,255,0.70), rgba(255,255,255,0.52));
      backdrop-filter: blur(8px) saturate(120%);
      box-shadow: var(--card-shadow);
      z-index:20;
      display:flex;
      flex-direction:column;
      gap:12px;
      color: #0f1724;
      overflow: auto;
    }

    /* keep compatibility with existing .sidebar-header element */
    .sidebar-header{
      display:flex;
      align-items:center;
      gap:10px;
      justify-content:space-between;
    }

    .sidebar-header h3{
      margin:0;
      font-weight:800;
      color:var(--resort-blue);
      font-size:1.05rem;
    }

    /* brand logo area (if you want to place an img inside .sidebar-header) */
    .brand-logo {
      width:46px; height:46px; border-radius:10px; display:inline-block;
      background: linear-gradient(90deg,var(--resort-blue),var(--resort-yellow));
      box-shadow:0 4px 12px rgba(0,0,0,0.08);
    }

    /* user block inside sidebar - reuse .user-info from second but restyle */
    .sidebar .user-info{
      margin-top:2px;
      padding:10px;
      border-radius:12px;
      background: rgba(255,255,255,0.35);
      display:flex;
      gap:12px;
      align-items:center;
      border:1px solid rgba(255,255,255,0.35);
      color: #08203a;
    }
    .sidebar .user-info img,
    .sidebar .user-info .avatar {
      width:48px; height:48px; border-radius:9999px; display:grid; place-items:center;
      font-size:20px; color:#fff;
      background: linear-gradient(120deg,var(--resort-blue),var(--resort-yellow));
      box-shadow:0 4px 12px rgba(0,0,0,0.08);
    }
    .sidebar .user-info .meta { font-size:0.9rem; }
    .sidebar .user-info .meta .name{ font-weight:700; color:#08203a; }
    .sidebar .user-info .meta .role{ font-size:0.8rem; color:#6b7280; }

    /* nav styling - keep .sidebar-menu and its li/a markup but look like first design */
    .sidebar-menu { margin-top:6px; display:block; }
    .sidebar-menu ul { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:6px; }
    .sidebar-menu li a{
      display:flex; align-items:center; gap:12px;
      padding:10px 12px; border-radius:10px; text-decoration:none;
      color: #0f1724; font-weight:700; transition: all .18s ease;
      background: transparent;
    }
    .sidebar-menu li a .material-icons{ color: var(--resort-blue); font-size:20px; }
    .sidebar-menu li a:hover,
    .sidebar-menu li.active a{
      transform:translateY(-2px);
      box-shadow: 0 8px 24px rgba(2,6,23,0.04);
      background: linear-gradient(90deg, rgba(0,123,255,0.06), rgba(255,204,0,0.04));
      color:#071633;
    }

    .sidebar .group-sep { height:1px; background: linear-gradient(90deg, rgba(2,6,23,0.04), rgba(2,6,23,0.02)); margin:10px 0; border-radius:2px; }

    .sidebar .logout {
      margin-top:auto; display:block; width:100%; padding:10px; border-radius:10px; text-align:left;
      font-weight:700; color:var(--resort-red);
      background: linear-gradient(180deg, rgba(255,255,255,0.4), rgba(255,255,255,0.25));
      border:1px solid rgba(239,68,68,0.08);
      cursor:pointer;
    }

    /* ---------- Main content area (apply first design look but map to .main-content) ---------- */
    .main-content{
      margin-left:290px; /* leave gap so sidebar floats like a card */
      padding:28px;
      width:100%;
      min-height:100vh;
      z-index:10;
    }

    .header {
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:16px;
    }

    .header h1{
      font-size:1.6rem;
      font-weight:800;
      color:#0b2540;
      margin:0;
    }

    /* search + profile controls (adapted from first design header-controls) */
    .header-controls { display:flex; gap:12px; align-items:center; }
    .search {
      display:flex; align-items:center; gap:8px;
      padding:8px 12px; border-radius:10px;
      background: rgba(255,255,255,0.7);
      box-shadow: 0 4px 18px rgba(2,6,23,0.04);
      border:1px solid rgba(2,6,23,0.03);
    }
    .search input{
      border:none; outline:none; background:transparent; width:180px; font-weight:600;
    }

    .profile-mini{
      display:flex; align-items:center; gap:10px;
      padding:8px 10px; border-radius:10px;
      background: linear-gradient(90deg, rgba(0,123,255,0.06), rgba(255,204,0,0.02));
      border:1px solid rgba(2,6,23,0.03);
      font-weight:700;
    }

    /* ---------- Summary cards (map .summary-card to card styles) ---------- */
    .summary-row{
      display:grid;
      grid-template-columns: repeat(auto-fit,minmax(240px,1fr));
      gap:18px;
      margin:18px 0 22px 0;
    }
    .summary-card{
      padding:18px;
      border-radius:12px;
      background: linear-gradient(180deg, rgba(255,255,255,0.85), rgba(255,255,255,0.70));
      backdrop-filter: blur(6px) saturate(120%);
      box-shadow: var(--card-shadow);
      border-left: 6px solid var(--resort-blue);
      min-height:110px;
      display:flex; flex-direction:column; justify-content:space-between;
    }
    .summary-card h4{ margin:0; font-size:0.95rem; color:#6b7280; font-weight:700; }
    .summary-card p{ margin:8px 0 0; font-size:1.9rem; font-weight:900; color:#081126; }

    /* accents for summary cards when inline style border-left-color is used (keeps user's inline colors) */
    .summary-card .accent-small{ width:36px; height:36px; border-radius:8px; display:grid; place-items:center; background: linear-gradient(90deg,var(--resort-blue),var(--resort-yellow)); color:#fff; font-weight:800; }

    /* ---------- Panel / table look ---------- */
    .table-panel{
      border-radius:12px;
      padding:16px;
      background: linear-gradient(180deg, rgba(255,255,255,0.92), rgba(255,255,255,0.78));
      box-shadow: var(--card-shadow);
      overflow:auto;
    }

    .muted{ color:#6b7280; font-weight:600; font-size:0.9rem }

    /* ---------- AI panel and cards mapping (map ai-inventory-section & ai-prediction-card to first design) ---------- */
    .ai-inventory-section{
      margin: 40px 0;
      border-radius:12px;
      padding:18px;
      background: linear-gradient(180deg, rgba(255,255,255,0.92), rgba(255,255,255,0.78));
      box-shadow: var(--card-shadow);
    }

    .ai-inventory-section .header{
      margin-bottom:12px;
      align-items:center;
    }

    .refresh-btn, .refresh {
      background:linear-gradient(90deg,#2f6bff,#3f86ff);
      color:#fff;border:0;padding:8px 14px;border-radius:12px;font-weight:700;
      display:inline-flex;align-items:center;gap:8px;cursor:pointer; box-shadow:0 10px 30px rgba(47,107,255,0.12);
      border: none;
    }

    .ai-predictions-grid{
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
      gap:16px;
    }
    .ai-prediction-card{
      background:#fff; border-radius:12px; padding:14px; box-shadow: 0 8px 26px rgba(2,6,23,0.04);
      display:flex; flex-direction:column; justify-content:space-between;
    }

    .ai-prediction-card .pred-head{
      display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;
    }

    /* badges */
    .badge{ display:inline-block; padding:6px 10px; border-radius:999px; font-size:12px; font-weight:800; text-transform:uppercase }
    .badge.pending{ background: rgba(255,204,0,0.12); color: #b45309; border:1px solid rgba(255,204,0,0.08) }
    .badge.approved{ background: rgba(0,123,255,0.10); color: var(--resort-blue); border:1px solid rgba(0,123,255,0.06) }
    .badge.rejected{ background: rgba(239,68,68,0.10); color: var(--resort-red); border:1px solid rgba(239,68,68,0.06) }

    /* risk badges pastel (keep the classes used in your second code) */
    .risk-high { background: rgba(239,68,68,0.08); color: #b91c1c; padding:6px 10px; border-radius:999px; font-weight:700; text-transform:capitalize; }
    .risk-medium { background: rgba(255,204,0,0.08); color:#b45309; padding:6px 10px; border-radius:999px; font-weight:700; text-transform:capitalize; }
    .risk-low { background: rgba(0,123,255,0.06); color:var(--resort-blue); padding:6px 10px; border-radius:999px; font-weight:700; text-transform:capitalize; }

    /* ---------- Misc / responsive (merge behaviors from both files) ---------- */
    @media (max-width: 980px){
      .sidebar{ left:12px; right:12px; width:calc(100% - 24px); top:12px; height:auto; position:relative; border-radius:12px; }
      .main-content{ margin-left:0; padding:16px; margin-top:12px; }
      .search input{ width:110px; }
      .summary-row{ grid-template-columns: repeat(2,1fr); }
      .ai-predictions-grid{ grid-template-columns: 1fr; }
    }
    @media (max-width: 640px){
      .header h1{ font-size:1.1rem; }
      .summary-card p{ font-size:1.45rem; }
      .search input{ width:80px; }
      .sidebar{ left:8px; right:8px; top:8px; }
    }

    /* small spin animation used on refresh icon */
    @keyframes spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
    .spin{ animation: spin 1s linear infinite }

    /* preserve older mobile menu button style from second file but styled to fit new theme */
    .mobile-menu-btn {
      display: none;
      position: fixed;
      top: 15px;
      left: 15px;
      background: var(--resort-blue);
      color: white;
      border: none;
      padding: 10px;
      border-radius: 8px;
      z-index: 1001;
      cursor: pointer;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    @media (max-width: 992px) {
      .mobile-menu-btn { display:block; }
    }

    /* breadcrumbs link style */
    .breadcrumbs { margin: 12px 0; color:#6b7280; font-weight:600; }
    .breadcrumbs a { color: var(--resort-blue); text-decoration:none; }
    .breadcrumbs a:hover { text-decoration: underline; }

  </style>
</head>
<body>
  <div class="bg-deco" aria-hidden="true"></div>

    <!-- SIDEBAR (original structure preserved) -->
    <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div style="display:flex; align-items:center; gap:10px;">

        <!-- REPLACED BRAND LOGO -->
        <div class="brand" style="display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center;">
            <img src="{{ asset('images/logo.png') }}"
                alt="Mayet Resort Logo"
                style="width:46px; height:46px; border-radius:10px; object-fit:cover;">
            <div style="font-weight:800; font-size:1rem;">Mayet Resort</div>
        </div>


        </div>
    </div>


    <!-- keep original sidebar user block (second code) but styled by CSS above -->
    <div class="user-info">
      <!-- If you used an <img> in your second code, keep it; otherwise fallback to initial -->
      <!-- Example: show avatar initial when no image present -->
      <div class="avatar">{{ strtoupper(substr(Auth::user()->full_name ?? 'A',0,1)) }}</div>
      <div class="meta">
        <div class="name">{{ Auth::user()->full_name ?? 'Administrator' }}</div>
        <div class="role">Administrator</div>
      </div>
    </div>

    <div class="sidebar-menu" aria-label="Main navigation">
      <h4 style="margin:0 0 6px 2px; color:#6b7280; font-size:12px; font-weight:700; text-transform:uppercase;">Main</h4>
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
        <li>
          <a href="#"><i class="material-icons">list_alt</i><span> <a href="{{ route('admin.requisitions.index') }}">View Requisitions</a></span></a>
        </li>
        <li>
          <a href="#"><i class="material-icons">people</i><span>Customers</span></a>
        </li>
        <li>
          <a href="#"><i class="material-icons">local_shipping</i><span>Suppliers</span></a>
        </li>
        <li>
          <a href="#"><i class="material-icons">assessment</i><span>Reports</span></a>
        </li>
      </ul>

      <div class="group-sep" aria-hidden="true"></div>

      <h4 style="margin:0 0 6px 2px; color:#6b7280; font-size:12px; font-weight:700; text-transform:uppercase;">System</h4>
      <ul>
        <li>
          <a href="#"><i class="material-icons">settings</i><span>Settings</span></a>
        </li>
        <li>
          <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="material-icons">logout</i>
            <span>Logout</span>
          </a>
          <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">@csrf</form>
        </li>
      </ul>
    </div>

  </div>

  <!-- Mobile Menu Button -->
  <button class="mobile-menu-btn" onclick="toggleSidebar()">
    <i class="material-icons" style="font-size:20px;">menu</i>
  </button>

  <!-- MAIN CONTENT (original structure preserved) -->
  <div class="main-content" id="mainContent">
    <!-- Header -->
    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:18px;">
      <div>
        <h1>Requisitions</h1>
        <div class="muted" style="margin-top:6px; font-size:0.95rem;">Manage and review all requisition requests</div>
      </div>

      <div class="header-controls">
        <div class="search" role="search" aria-label="Search inventory" style="display:flex; align-items:center;">
          <i class="material-icons" style="color:var(--resort-blue)">search</i>
          <input placeholder="Search products..." />
        </div>

        <div class="profile-mini" title="{{ Auth::user()->full_name ?? 'Admin' }}">
          <i class="material-icons">notifications</i>
          <div style="font-weight:700;">{{ Auth::user()->full_name ?? 'Admin' }}</div>
        </div>
      </div>
    </div>

    <!-- Breadcrumbs (kept) -->
    <div class="breadcrumbs">
      <a href="{{ route('admin.dashboard') }}">Home</a> / Dashboard
    </div>

    <!-- Summary cards (keeps markup but styled) -->
    <div class="summary-row" aria-label="Summary cards">
      <div class="summary-card" style="border-left-color: #28a745;">
        <h4>In Stock</h4>
        <p>{{ $inStockCount ?? 0 }}</p>
      </div>

      <div class="summary-card" style="border-left-color: #f59e0b;">
        <h4>Low Stock</h4>
        <p>{{ $lowStockCount ?? 0 }}</p>
      </div>

      <div class="summary-card" style="border-left-color: #ef4444;">
        <h4>Out of Stock</h4>
        <p>{{ $outOfStockCount ?? 0 }}</p>
      </div>
    </div>

    <!-- AI Inventory Section (structure preserved but styles applied) -->
    <div class="ai-inventory-section" aria-label="AI Inventory Predictions">
      <div class="header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 16px;">
        <h2 style="margin:0; font-size:1.25rem; font-weight:800; color:#0b2540;">AI Inventory Predictions</h2>
        <button class="refresh" onclick="refreshAIData()">
          <i class="material-icons" id="refreshIcon">refresh</i> Refresh
        </button>
      </div>

      @if(isset($aiPredictions) && count($aiPredictions) > 0)
        <div class="ai-predictions-grid" aria-live="polite">
          @foreach($aiPredictions as $productId => $prediction)
            @php
              $product = \App\Models\Product::with(['supplier', 'category'])->find($productId);
              $riskClass = strtolower($prediction['risk_level'] ?? 'low');
            @endphp

            <article class="ai-prediction-card" aria-labelledby="prod-{{ $productId }}">
              <div class="pred-head">
                <h4 id="prod-{{ $productId }}" style="margin:0; font-size:1rem; font-weight:700; color:#08203a;">{{ $product->name ?? 'Unknown Product' }}</h4>
                <span class="badge risk-{{ $riskClass }}">{{ $prediction['risk_level'] ?? 'Low' }} Risk</span>
              </div>

              <div id="forecast-{{ $productId }}" style="height:150px; margin-bottom:12px;"></div>

              <div style="font-size:13px; color:#475569; line-height:1.5;">
                <p style="margin:6px 0;"><strong>📈 Forecast:</strong> {{ number_format($prediction['forecasted_usage_30d'] ?? 0) }} units next 30 days</p>
                <p style="margin:6px 0;"><strong>🔍 Trend:</strong> {{ $prediction['insights']['trend'] ?? 'No trend data' }}</p>
                <p style="margin:6px 0;"><strong>🔄 Pattern:</strong> {{ $prediction['insights']['seasonality'] ?? 'No pattern detected' }}</p>
                <p style="margin:6px 0;"><strong>💡 Recommendation:</strong> {{ $prediction['insights']['recommended_action'] ?? 'No recommendation available' }}</p>
              </div>

              <div style="margin-top:10px; font-size:12px; color:{{ ($prediction['confidence'] ?? 0) > 0.7 ? '#2e7d32' : (($prediction['confidence'] ?? 0) > 0.4 ? '#b45309' : '#b91c1c') }}; text-align:right;">
                <i class="fas fa-chart-line"></i> Forecast Confidence: {{ round(($prediction['confidence'] ?? 0) * 100) }}%
              </div>
            </article>
          @endforeach
        </div>
      @else
        <div style="text-align: center; padding: 40px 20px; color: #666;">
          <i class="material-icons" style="font-size: 48px; color: #ddd; margin-bottom: 10px;">insights</i>
          <p>No prediction data available. The system needs more usage data to generate accurate forecasts.</p>
        </div>
      @endif
    </div>

    <!-- Pending Requisitions Section -->
    <div class="ai-inventory-section" aria-label="Pending Requisitions" style="margin-top: 30px;">
      <div class="header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 16px;">
        <h2 style="margin:0; font-size:1.25rem; font-weight:800; color:#0b2540; display: flex; align-items: center;">
          <i class="material-icons" style="margin-right: 8px; color: #d97706;">pending_actions</i>
          Pending Requisitions
          @if($pendingRequisitionsCount > 0)
            <span class="badge" style="background-color: #f59e0b; color: white; font-size: 0.7rem; padding: 2px 8px; border-radius: 10px; margin-left: 8px;">
              {{ $pendingRequisitionsCount }} pending
            </span>
          @endif
        </h2>
        <a href="{{ route('admin.requisitions.index') }}" class="view-all" style="color: var(--resort-blue); text-decoration: none; font-size: 0.9rem; display: flex; align-items: center;">
          View All <i class="material-icons" style="font-size: 16px; margin-left: 4px;">arrow_forward</i>
        </a>
      </div>

      @if($pendingRequisitions->isNotEmpty())
        <div class="requisitions-list" style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
          @foreach($pendingRequisitions as $requisition)
            <a href="{{ route('admin.requisitions.show', $requisition) }}" class="requisition-item" style="display: block; padding: 16px; border-bottom: 1px solid #f1f5f9; text-decoration: none; color: inherit; transition: background-color 0.2s;">
              <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                  <h4 style="margin: 0 0 6px 0; font-size: 1rem; color: #1e293b; font-weight: 600;">
                    {{ $requisition->item->name }}
                    <span style="color: #64748b; font-weight: 500;">x{{ $requisition->quantity }}</span>
                  </h4>
                  <div style="display: flex; align-items: center; font-size: 0.85rem; color: #64748b; margin-bottom: 4px;">
                    <i class="material-icons" style="font-size: 16px; margin-right: 4px;">person</i>
                    {{ $requisition->requester->first_name }} {{ $requisition->requester->last_name }}
                  </div>
                  <div style="display: flex; align-items: center; font-size: 0.8rem; color: #94a3b8;">
                    <i class="material-icons" style="font-size: 14px; margin-right: 4px;">schedule</i>
                    {{ $requisition->created_at->diffForHumans() }}
                  </div>
                </div>
                <div style="color: #d97706; font-size: 0.85rem; font-weight: 500; display: flex; align-items: center;">
                  <span class="dot" style="display: inline-block; width: 8px; height: 8px; background-color: #f59e0b; border-radius: 50%; margin-right: 6px;"></span>
                  Pending
                </div>
              </div>
            </a>
          @endforeach
        </div>
      @else
        <div style="background: white; border-radius: 8px; padding: 30px; text-align: center; color: #64748b; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
          <i class="material-icons" style="font-size: 48px; color: #e2e8f0; margin-bottom: 10px;">inventory_2</i>
          <p style="margin: 10px 0 0; font-size: 0.95rem;">No pending requisitions at the moment.</p>
        </div>
      @endif
    </div>
    <!-- Debug Section -->
    <div class="ai-inventory-section" style="margin-top: 30px; background-color: #f8f9fa; border: 1px solid #e9ecef;">
      <h3>Debug Information</h3>
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background-color: #e9ecef;">
              <th style="padding: 8px; text-align: left; border: 1px solid #dee2e6;">ID</th>
              <th style="padding: 8px; text-align: left; border: 1px solid #dee2e6;">Product Name</th>
              <th style="padding: 8px; text-align: right; border: 1px solid #dee2e6;">Quantity</th>
              <th style="padding: 8px; text-align: right; border: 1px solid #dee2e6;">Threshold</th>
              <th style="padding: 8px; text-align: center; border: 1px solid #dee2e6;">Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($productStatuses as $product)
              <tr style="border-bottom: 1px solid #dee2e6;">
                <td style="padding: 8px; border: 1px solid #dee2e6;">{{ $product['id'] }}</td>
                <td style="padding: 8px; border: 1px solid #dee2e6;">{{ $product['name'] }}</td>
                <td style="padding: 8px; text-align: right; border: 1px solid #dee2e6;">{{ $product['quantity'] }}</td>
                <td style="padding: 8px; text-align: right; border: 1px solid #dee2e6;">{{ $product['threshold'] }}</td>
                <td style="padding: 8px; text-align: center; border: 1px solid #dee2e6;">
                  @if($product['status'] === 'out_of_stock')
                    <span style="color: #ef4444; font-weight: 600;">Out of Stock</span>
                  @elseif($product['status'] === 'low_stock')
                    <span style="color: #f59e0b; font-weight: 600;">Low Stock</span>
                  @else
                    <span style="color: #28a745; font-weight: 600;">In Stock</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div> <!-- Close main-content -->
  </div> <!-- Close page-container -->

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

    // Submenu toggles - keep behavior from second code but tolerant if elements missing
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.has-submenu > a').forEach(btn => {
        btn.addEventListener('click', e => {
          e.preventDefault();
          const parent = btn.parentElement;
          const submenu = parent.querySelector('.submenu');
          // close others (same behavior as second file did)
          document.querySelectorAll('.has-submenu').forEach(item => {
            if (item !== parent && item.classList.contains('active')) {
              item.classList.remove('active');
              const sm = item.querySelector('.submenu');
              if (sm) sm.classList.remove('show');
            }
          });
          parent.classList.toggle("active");
          if (submenu) submenu.classList.toggle("show");
        });
      });

      // Initialize Chart.js for each prediction (preserves Blade loop variables)
      @if(isset($aiPredictions))
        @foreach($aiPredictions as $productId => $prediction)
          @if(isset($prediction['forecast_data']) && is_array($prediction['forecast_data']))
            (function() {
              const ctxEl = document.getElementById('forecast-{{ $productId }}');
              if (!ctxEl) return;
              const ctx = ctxEl.getContext('2d');
              new Chart(ctx, {
                type: 'line',
                data: {
                  labels: {!! json_encode(array_column($prediction['forecast_data'], 'ds')) !!},
                  datasets: [{
                    label: 'Forecasted Usage',
                    data: {!! json_encode(array_column($prediction['forecast_data'], 'yhat')) !!},
                    borderColor: 'rgba(54, 162, 235, 1)',
                    tension: 0.2,
                    fill: true,
                    backgroundColor: 'rgba(54, 162, 235, 0.08)'
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
            })();
          @endif
        @endforeach
      @endif
    });

    // Refresh AI Data (keeps UX from second file but uses new spin animation)
    function refreshAIData() {
      const btn = document.querySelector('.refresh');
      const icon = document.getElementById('refreshIcon');
      if (btn) btn.disabled = true;
      if (icon) icon.classList.add('spin');
      setTimeout(() => {
        window.location.reload();
      }, 900);
    }
  </script>
</body>
</html>
