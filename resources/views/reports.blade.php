@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>Reports & Analytics</h1>
            <p class="lead">Product analytics and visualization reports</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <h2 class="card-text">{{ $products->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Value</h5>
                    <h2 class="card-text">${{ number_format($products->sum(fn($p) => $p->price * $p->quantity), 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Avg Price</h5>
                    <h2 class="card-text">${{ number_format($products->avg('price'), 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Categories</h5>
                    <h2 class="card-text">{{ $categoryData->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5 class="card-title">Products by Category</h5></div>
                <div class="card-body">
                    <canvas id="categoryChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5 class="card-title">Products by Price Range</h5></div>
                <div class="card-body">
                    <canvas id="priceRangeChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Sales Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h5 class="card-title">Monthly Sales</h5></div>
                <div class="card-body">
                    <canvas id="monthlySalesChart" width="400" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table Preview -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Products List</h5>
                    <a href="{{ route('data-table') }}" class="btn btn-primary btn-sm">View Full Table</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Category</th>
                                    <th>Total Value</th>
                                    <th>Created Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products->take(5) as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>${{ number_format($product->price, 2) }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td><span class="badge bg-secondary">{{ $product->category }}</span></td>
                                    <td>${{ number_format($product->price * $product->quantity, 2) }}</td>
                                    <td>{{ $product->created_date->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'pie',
    data: {
        labels: {!! json_encode($categoryData->pluck('category')) !!},
        datasets: [{
            data: {!! json_encode($categoryData->pluck('count')) !!},
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
        }]
    },
    options: { responsive: true }
});

// Price Range Chart
const priceRangeCtx = document.getElementById('priceRangeChart').getContext('2d');
new Chart(priceRangeCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($priceRangeData->pluck('price_range')) !!},
        datasets: [{
            label: 'Number of Products',
            data: {!! json_encode($priceRangeData->pluck('count')) !!},
            backgroundColor: '#36A2EB'
        }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

// Monthly Sales Chart
const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
new Chart(monthlySalesCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Sales ($)',
            data: {!! json_encode(array_values($monthlySales->pluck('total_sales')->toArray())) !!},
            borderColor: '#FF6384',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>
@endpush