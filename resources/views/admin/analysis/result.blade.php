@extends('admin::index')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Analysis Result</h3>
    </div>
    <div class="box-body">
        <h4>Transaction Patterns</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order Date</th>
                    <th>No Order</th>
                    <th>Product Name</th>
                    <th>Product Price</th>
                    <th>Product Qty</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactionPatterns as $pattern)
                    <tr>
                        <td>{{ $pattern['order_date'] }}</td>
                        <td>{{ $pattern['no_order'] }}</td>
                        <td>{{ $pattern['product_name'] }}</td>
                        <td>{{ $pattern['product_price'] }}</td>
                        <td>{{ $pattern['product_qty'] }}</td>
                        <td>{{ $pattern['status'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4>Product Frequency</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Probability</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productFrequency as $product)
                    <tr>
                        <td>{{ $product['product_name'] }}</td>
                        <td>{{ $product['probability'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4>Suggested Products</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Accuracy</th>
                    <th>Precision</th>
                    <th>Recall</th>
                    <th>F1 Score</th>
                    <th>AUC</th>
                    <th>F Rate</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $suggestedProducts['accuracy'] }}</td>
                    <td>{{ $suggestedProducts['precision'] }}</td>
                    <td>{{ $suggestedProducts['recall'] }}</td>
                    <td>{{ $suggestedProducts['f1_score'] }}</td>
                    <td>{{ $suggestedProducts['auc'] }}</td>
                    <td>{{ $suggestedProducts['f_rate'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
