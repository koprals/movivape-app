
<div class="row">
    <div class="col-lg-4 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>Rp. {{ number_format($totalSales, 2) }}</h3>
                <p>Total Sales (This Month)</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $totalVolume }}</h3>
                <p>Total Volume (This Month)</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>Top 5 Products</h3>
                <p>Frequently Bought (This Month)</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
        </div>
    </div>
</div>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Top 5 Products (This Month)</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $product)
                    <tr>
                        <td>{{ $product->product->name }}</td>
                        <td>{{ $product->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Sales Growth (This Year)</h3>
    </div>
    <div class="box-body">
        <canvas id="salesGrowthChart"></canvas>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('salesGrowthChart').getContext('2d');
    var salesGrowthData = {
        labels: {!! json_encode($salesGrowth->pluck('month')->map(function($month) {
            return DateTime::createFromFormat('!m', $month)->format('F');
        })) !!},
        datasets: [{
            label: 'Total Sales',
            data: {!! json_encode($salesGrowth->pluck('total_sales')) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };
    var salesGrowthChart = new Chart(ctx, {
        type: 'line',
        data: salesGrowthData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
