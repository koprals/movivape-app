
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Step 3: Test Data Generation</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order Date</th>
                    <th>No Order</th>
                    <th>Product Name</th>
                    <th>Product Price</th>
                    <th>Product Quantity</th>
                    <th>Status</th>
                    <th>Predicted Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($testData as $data)
                    <tr>
                        <td>{{ $data['order_date'] }}</td>
                        <td>{{ $data['no_order'] }}</td>
                        <td>{{ $data['product_name'] }}</td>
                        <td>{{ $data['product_price'] }}</td>
                        <td>{{ $data['product_qty'] }}</td>
                        <td>{{ $data['status'] }}</td>
                        <td>{{ $data['predicted_status'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="box-footer">
        <a href="{{ route('admin.analysis.step4') }}" class="btn btn-primary">Next Step</a>
    </div>
</div>
