<?php
// --- KHÔNG CẦN KẾT NỐI DATABASE ---

// 1. Tạo dữ liệu giả lập cho KPI (Thay vì lấy từ bảng kpi_stats)
$kpi_data = [
    'teus_today' => 12450,
    'ships_count' => 8,
    'trucks_gate' => 145,
    'berth_prod' => 55
];

// 2. Tạo dữ liệu giả lập cho Danh sách tàu (Thay vì lấy từ bảng ships)
$ships_list = [
    [
        'ship_name' => 'MAERSK HANOI',
        'voyage_number' => 'MSK-001',
        'eta' => '2026-01-20 14:00:00',
        'location' => 'Cầu B1',
        'shipping_line' => 'Maersk',
        'status' => 'working'
    ],
    [
        'ship_name' => 'ONE ORPHEUS',
        'voyage_number' => 'ONE-223',
        'eta' => '2026-01-20 15:30:00',
        'location' => 'Phao số 0',
        'shipping_line' => 'ONE',
        'status' => 'waiting'
    ],
    [
        'ship_name' => 'CMA CGM ALASKA',
        'voyage_number' => 'CMA-889',
        'eta' => '2026-01-20 10:00:00',
        'location' => 'Cầu B3',
        'shipping_line' => 'CMA CGM',
        'status' => 'working'
    ],
    [
        'ship_name' => 'WAN HAI 305',
        'voyage_number' => 'WH-305',
        'eta' => '2026-01-20 18:00:00',
        'location' => 'Biển Đông',
        'shipping_line' => 'Wan Hai',
        'status' => 'incoming'
    ],
    [
        'ship_name' => 'EVER GIVEN',
        'voyage_number' => 'EV-999',
        'eta' => '2026-01-21 08:00:00',
        'location' => 'Phao số 1',
        'shipping_line' => 'Evergreen',
        'status' => 'incoming'
    ]
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SNP Control Center</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <i class="fa-solid fa-anchor fa-2x"></i>
                <div class="logo-text"><span>TỔNG CÔNG TY</span><strong>TÂN CẢNG SÀI GÒN</strong></div>
            </div>
            <div class="user-info">
                <div class="avatar"><i class="fa-solid fa-user-tie"></i></div>
                <div><p class="name">Admin</p><p class="role">Trực Ban Điều Hành</p></div>
            </div>
            <nav class="menu">
                <a href="#" class="active"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
                <a href="#"><i class="fa-solid fa-ship"></i> Kế hoạch bến</a>
                <a href="#"><i class="fa-solid fa-truck-fast"></i> Cổng & Vận tải</a>
                <a href="#"><i class="fa-solid fa-layer-group"></i> Bãi Container</a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="top-bar">
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Tìm kiếm container, vận đơn, tàu...">
                </div>
                <div class="actions"><i class="fa-solid fa-bell"></i></div>
            </header>

            <div class="dashboard-grid">
                <div class="kpi-row">
                    <div class="card kpi-card">
                        <div class="kpi-info"><h3>Sản lượng 24h</h3><h2><?= number_format($kpi_data['teus_today']) ?> <small>TEUs</small></h2></div>
                        <div class="kpi-icon color-blue"><i class="fa-solid fa-box"></i></div>
                    </div>
                    <div class="card kpi-card">
                        <div class="kpi-info"><h3>Tàu tại cảng</h3><h2><?= $kpi_data['ships_count'] ?> <small>Tàu</small></h2></div>
                        <div class="kpi-icon color-green"><i class="fa-solid fa-ship"></i></div>
                    </div>
                    <div class="card kpi-card">
                        <div class="kpi-info"><h3>Xe chờ cổng</h3><h2><?= $kpi_data['trucks_gate'] ?> <small>Xe</small></h2></div>
                        <div class="kpi-icon color-orange"><i class="fa-solid fa-truck"></i></div>
                    </div>
                    <div class="card kpi-card">
                        <div class="kpi-info"><h3>Năng suất QC</h3><h2><?= $kpi_data['berth_prod'] ?> <small>Move/h</small></h2></div>
                        <div class="kpi-icon color-red"><i class="fa-solid fa-stopwatch"></i></div>
                    </div>
                </div>

                <div class="visual-row">
                    <div class="card map-section">
                        <div class="map-overlay"><i class="fa-solid fa-satellite"></i> Giám sát Cát Lái</div>
                        <div id="portMap"></div>
                    </div>
                    <div class="charts-column">
                        <div class="card chart-card">
                            <div class="card-header"><h3>Hiệu suất Cầu bờ</h3></div>
                            <div class="chart-box"><canvas id="berthChart"></canvas></div>
                        </div>
                        <div class="card chart-card">
                            <div class="card-header"><h3>Lưu lượng Cổng</h3></div>
                            <div class="chart-box"><canvas id="gateChart"></canvas></div>
                        </div>
                    </div>
                </div>

                <div class="card table-section">
                    <div class="card-header"><h3>Kế hoạch Tàu cập bến (Real-time)</h3></div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr><th>Tên Tàu</th><th>Chuyến</th><th>ETA</th><th>Vị trí</th><th>Hãng</th><th>Trạng Thái</th></tr>
                            </thead>
                            <tbody>
                                <?php
                                // Vòng lặp lấy dữ liệu từ Mảng $ships_list thay vì Database
                                foreach($ships_list as $row) {
                                    // Logic màu sắc trạng thái
                                    $cls = ''; $txt = '';
                                    if ($row['status'] == 'working') {
                                        $cls = 'green'; $txt = 'Làm hàng';
                                    } elseif ($row['status'] == 'waiting') {
                                        $cls = 'orange'; $txt = 'Chờ cầu';
                                    } else {
                                        $cls = 'blue'; $txt = 'Đang đến';
                                    }

                                    $eta_formatted = date("d/m H:i", strtotime($row['eta']));
                                    
                                    echo "<tr>
                                        <td><strong>{$row['ship_name']}</strong></td>
                                        <td>{$row['voyage_number']}</td>
                                        <td>{$eta_formatted}</td>
                                        <td>{$row['location']}</td>
                                        <td>{$row['shipping_line']}</td>
                                        <td><span class='badge {$cls}'>{$txt}</span></td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bottom-row">
                    <div class="card">
                        <div class="card-header"><h3><i class="fa-solid fa-list-ul"></i> Nhật ký hoạt động</h3></div>
                        <div class="activity-list">
                            <div class="act-item">
                                <span class="time">10:45</span>
                                <div><strong>Hạ bãi thành công</strong><p>Cont TCKU123456 tại A-05-02</p></div>
                            </div>
                            <div class="act-item">
                                <span class="time">10:42</span>
                                <div><strong>Tàu cập cầu</strong><p>CMA CGM ALASKA bắt đầu neo</p></div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header"><h3><i class="fa-solid fa-robot"></i> Tình trạng Thiết bị</h3></div>
                        <div class="eq-grid">
                            <div class="eq-item"><span>Cẩu QC-01</span><strong class="text-green">Hoạt động</strong></div>
                            <div class="eq-item"><span>Cẩu QC-02</span><strong class="text-red">Bảo trì</strong></div>
                            <div class="eq-item"><span>RTG-05</span><strong class="text-green">Hoạt động</strong></div>
                            <div class="eq-item"><span>Xe nâng 01</span><strong class="text-orange">Chờ lệnh</strong></div>
                        </div>
                    </div>
                </div>
            </div>
            <footer>&copy; 2026 Sai Gon Newport Corporation.</footer>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="dashboard.js"></script>
</body>
</html>