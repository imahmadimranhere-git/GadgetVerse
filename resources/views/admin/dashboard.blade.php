<x-app-layout>

    <h2 class="mb-4 fw-bold">📊 Admin Dashboard</h2>

    <!-- Stats Cards Row -->
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Sales</p>
                            <h4 class="fw-bold mb-0">Rs {{ number_format($totalSales, 0) }}</h4>
                        </div>
                        <i class="bi bi-currency-dollar fs-1 text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Orders</p>
                            <h4 class="fw-bold mb-0">{{ $totalOrders }}</h4>
                        </div>
                        <i class="bi bi-bag-check fs-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Customers</p>
                            <h4 class="fw-bold mb-0">{{ $totalUsers }}</h4>
                        </div>
                        <i class="bi bi-people fs-1 text-info"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Products</p>
                            <h4 class="fw-bold mb-0">{{ $totalProducts }}</h4>
                        </div>
                        <i class="bi bi-box-seam fs-1 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Chart + Recent Orders Row -->
    <div class="row g-3">

        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Sales Overview</h5>
                    <canvas id="salesChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Recent Orders</h5>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Order#</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentOrders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ ucfirst($order->order_status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No orders yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('salesChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Sales (Rs)',
                    data: [1200, 1900, 800, 1500, 2200, 1700, 2400],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    </script>

</x-app-layout>