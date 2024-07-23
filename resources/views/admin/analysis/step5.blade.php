
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Step 5: Confusion Matrix Calculation</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Accuracy</td>
                    <td>{{ $confusionMatrix['accuracy'] }}</td>
                </tr>
                <tr>
                    <td>Precision</td>
                    <td>{{ $confusionMatrix['precision'] }}</td>
                </tr>
                <tr>
                    <td>Recall</td>
                    <td>{{ $confusionMatrix['recall'] }}</td>
                </tr>
                <tr>
                    <td>F1-Score</td>
                    <td>{{ $confusionMatrix['f1_score'] }}</td>
                </tr>
                <tr>
                    <td>AUC</td>
                    <td>{{ $confusionMatrix['auc'] }}</td>
                </tr>
                <tr>
                    <td>F-Rate</td>
                    <td>{{ $confusionMatrix['f_rate'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="box-footer">
        <a href="{{ route('admin.analysis') }}" class="btn btn-primary">Finish</a>
    </div>
</div>
