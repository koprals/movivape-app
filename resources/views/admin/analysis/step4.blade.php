
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Step 4: Probability Calculation</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Probability Sering Dibeli</th>
                    <th>Probability Jarang Dibeli</th>
                </tr>
            </thead>
            <tbody>
                @foreach($probabilities as $productName => $probability)
                    <tr>
                        <td>{{ $productName }}</td>
                        <td>{{ $probability['Sering Dibeli'] }}</td>
                        <td>{{ $probability['Jarang Dibeli'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="box-footer">
        <a href="{{ route('admin.analysis.step5') }}" class="btn btn-primary">Next Step</a>
    </div>
</div>
