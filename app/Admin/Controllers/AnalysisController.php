<?php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\TrainingData;
use App\Models\TestData;
use App\Models\Probability;
use App\Models\ConfusionMatrix;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    protected $title = 'Naive Bayes Analysis';

    public function index(Content $content)
    {
        return $content
            ->title($this->title)
            ->description('Generate Analysis')
            ->body($this->form());
    }

    protected function form()
    {
        $form = new \Encore\Admin\Widgets\Form();

        $form->select('time_range', 'Select Time Range')->options([
            'monthly' => 'Monthly',
            'yearly' => 'Yearly',
        ])->default('monthly');

        $form->text('time_value', 'Select Month/Year')->placeholder('YYYY-MM or YYYY');
        $form->action(route('admin.analysis.step1'));

        return $form;
    }

    public function step1(Request $request, Content $content)
    {
        $timeRange = $request->input('time_range');
        $timeValue = $request->input('time_value');

        // Mengambil data dari database
        $orders = Order::with('orderDetails.product')
            ->when($timeRange === 'monthly', function ($query) use ($timeValue) {
                return $query->whereMonth('order_date', '=', substr($timeValue, 5, 2))
                             ->whereYear('order_date', '=', substr($timeValue, 0, 4));
            })
            ->when($timeRange === 'yearly', function ($query) use ($timeValue) {
                return $query->whereYear('order_date', '=', $timeValue);
            })
            ->get();

        // Tahap 1: Pengumpulan Data
        $dataTraining = $this->collectTrainingData($orders);
        session(['dataTraining' => $dataTraining]);

        return $content
            ->title($this->title)
            ->description('Step 1: Data Collection')
            ->body(view('admin.analysis.step1', compact('dataTraining')));
    }

    public function step2(Content $content)
    {
        // Tahap 2: Input Data Training
        $dataTraining = session('dataTraining');
        $this->saveTrainingData($dataTraining);

        return $content
            ->title($this->title)
            ->description('Step 2: Training Data Input')
            ->body(view('admin.analysis.step2', compact('dataTraining')));
    }

    public function step3(Content $content)
    {
        // Tahap 3: Test Data
        $dataTraining = session('dataTraining');
        $testData = $this->generateTestData($dataTraining);
        $this->saveTestData($testData);
        session(['testData' => $testData]);

        return $content
            ->title($this->title)
            ->description('Step 3: Test Data Generation')
            ->body(view('admin.analysis.step3', compact('testData')));
    }

    public function step4(Content $content)
    {
        // Tahap 4: Perhitungan Probabilitas
        $testData = session('testData');
        $probabilities = $this->calculateProbabilities($testData);
        $this->saveProbabilities($probabilities);
        session(['probabilities' => $probabilities]);

        return $content
            ->title($this->title)
            ->description('Step 4: Probability Calculation')
            ->body(view('admin.analysis.step4', compact('probabilities')));
    }

    public function step5(Content $content)
    {
        // Tahap 5: Confusion Matrix
        $testData = session('testData');
        $probabilities = session('probabilities');
        $confusionMatrix = $this->calculateConfusionMatrix($testData, $probabilities);
        $this->saveConfusionMatrix($confusionMatrix);

        return $content
            ->title($this->title)
            ->description('Step 5: Confusion Matrix Calculation')
            ->body(view('admin.analysis.step5', compact('confusionMatrix')));
    }

    private function collectTrainingData($orders)
    {
        // Mengumpulkan data dari tabel product, order, dan order detail
        $dataTraining = [];
        foreach ($orders as $order) {
            foreach ($order->orderDetails as $detail) {
                $dataTraining[] = [
                    'order_date' => $order->order_date,
                    'no_order' => $order->no_order,
                    'product_name' => $detail->product->product_name,
                    'product_price' => $detail->product->price,
                    'product_qty' => $detail->product_qty,
                    'status' => $detail->product_qty > 2 ? 'Sering Dibeli' : 'Jarang Dibeli',
                ];
            }
        }
        return $dataTraining;
    }

    private function saveTrainingData($dataTraining)
    {
        // Simpan data training ke database
        TrainingData::insert($dataTraining);
    }

    private function generateTestData($dataTraining)
    {
        // Generate test data berdasarkan data training
        $testData = [];
        foreach ($dataTraining as $data) {

            $testData[] = array_merge($data, [
                'predicted_status' => $this->predictStatus($data),
            ]);
        }

        return $testData;
    }

    private function saveTestData($testData)
    {
        // Simpan test data ke database
        TestData::insert($testData);
    }

    private function predictStatus($data)
    {
        // Prediksi status berdasarkan data training
        return $data['product_qty'] > 2 ? 'Sering Dibeli' : 'Jarang Dibeli';
    }

    private function calculateProbabilities($testData)
    {
        // Tentukan Prior Probabilities
        $totalData = count($testData);
        $totalSeringDibeli = count(array_filter($testData, function($data) {
            return $data['status'] == 'Sering Dibeli';
        }));
        $totalJarangDibeli = $totalData - $totalSeringDibeli;

        $priorSeringDibeli = $totalSeringDibeli / $totalData;
        $priorJarangDibeli = $totalJarangDibeli / $totalData;

        // Hitung Likelihood untuk setiap fitur dalam setiap kelas
        $meanPriceSeringDibeli = $this->calculateMean(array_filter($testData, function($data) {
            return $data['status'] == 'Sering Dibeli';
        }), 'product_price');
        $stdDevPriceSeringDibeli = $this->calculateStdDev(array_filter($testData, function($data) {
            return $data['status'] == 'Sering Dibeli';
        }), 'product_price');

        $meanQtySeringDibeli = $this->calculateMean(array_filter($testData, function($data) {
            return $data['status'] == 'Sering Dibeli';
        }), 'product_qty');
        $stdDevQtySeringDibeli = $this->calculateStdDev(array_filter($testData, function($data) {
            return $data['status'] == 'Sering Dibeli';
        }), 'product_qty');

        $meanPriceJarangDibeli = $this->calculateMean(array_filter($testData, function($data) {
            return $data['status'] == 'Jarang Dibeli';
        }), 'product_price');
        $stdDevPriceJarangDibeli = $this->calculateStdDev(array_filter($testData, function($data) {
            return $data['status'] == 'Jarang Dibeli';
        }), 'product_price');

        $meanQtyJarangDibeli = $this->calculateMean(array_filter($testData, function($data) {
            return $data['status'] == 'Jarang Dibeli';
        }), 'product_qty');
        $stdDevQtyJarangDibeli = $this->calculateStdDev(array_filter($testData, function($data) {
            return $data['status'] == 'Jarang Dibeli';
        }), 'product_qty');

        // Hitung posterior probabilities
        $probabilities = [];
        foreach ($testData as $data) {
            $likelihoodSeringDibeli = $this->calculateGaussianValue($data['product_price'], $meanPriceSeringDibeli, $stdDevPriceSeringDibeli) *
                                      $this->calculateGaussianValue($data['product_qty'], $meanQtySeringDibeli, $stdDevQtySeringDibeli);

            $likelihoodJarangDibeli = $this->calculateGaussianValue($data['product_price'], $meanPriceJarangDibeli, $stdDevPriceJarangDibeli) *
                                       $this->calculateGaussianValue($data['product_qty'], $meanQtyJarangDibeli, $stdDevQtyJarangDibeli);

            $posteriorSeringDibeli = $priorSeringDibeli * $likelihoodSeringDibeli;
            $posteriorJarangDibeli = $priorJarangDibeli * $likelihoodJarangDibeli;

            $totalPosterior = $posteriorSeringDibeli + $posteriorJarangDibeli;

            $probabilities[$data['product_name']] = [
                'Sering Dibeli' => $posteriorSeringDibeli / $totalPosterior,
                'Jarang Dibeli' => $posteriorJarangDibeli / $totalPosterior
            ];
        }

        return $probabilities;
    }

    private function calculateGaussianValue($value, $mean, $stdDev)
    {
        // Hitung nilai Gaussian untuk nilai tertentu
        return (1 / ($stdDev * sqrt(2 * pi()))) * exp(-pow($value - $mean, 2) / (2 * pow($stdDev, 2)));
    }

    private function saveProbabilities($probabilities)
    {
        // Simpan probabilitas ke database
        $data = [];
        foreach ($probabilities as $productName => $probability) {
            $data[] = [
                'product_name' => $productName,
                'probability_sering_dibeli' => $probability['Sering Dibeli'],
                'probability_jarang_dibeli' => $probability['Jarang Dibeli'],
            ];
        }

        Probability::insert($data);
    }

    private function calculateConfusionMatrix($testData, $probabilities)
    {
        // Hitung confusion matrix
        $tp = $fp = $tn = $fn = 0;
        foreach ($testData as $data) {
            $predictedStatus = $probabilities[$data['product_name']]['Sering Dibeli'] > $probabilities[$data['product_name']]['Jarang Dibeli'] ? 'Sering Dibeli' : 'Jarang Dibeli';

            if ($data['status'] == 'Sering Dibeli' && $predictedStatus == 'Sering Dibeli') {
                $tp++;
            } elseif ($data['status'] == 'Jarang Dibeli' && $predictedStatus == 'Jarang Dibeli') {
                $tn++;
            } elseif ($data['status'] == 'Sering Dibeli' && $predictedStatus == 'Jarang Dibeli') {
                $fn++;
            } else {
                $fp++;
            }
        }

        $accuracy = ($tp + $tn) / ($tp + $tn + $fp + $fn);
        $precision = $tp / ($tp + $fp);
        $recall = $tp / ($tp + $fn);
        $f1_score = 2 * (($precision * $recall) / ($precision + $recall));
        $auc = $this->calculateAUC($tp, $fp, $tn, $fn);
        $f_rate = $this->calculateFRate($tp, $fp, $tn, $fn);

        return compact('accuracy', 'precision', 'recall', 'f1_score', 'auc', 'f_rate');
    }

    private function calculateMean($data, $field = 'product_qty')
    {
        // Hitung rata-rata untuk field tertentu
        return array_sum(array_column($data, $field)) / count($data);
    }

    private function calculateStdDev($data, $field = 'product_qty')
    {
        // Hitung standar deviasi untuk field tertentu
        $mean = $this->calculateMean($data, $field);
        $variance = array_sum(array_map(function($value) use ($mean) {
            return pow($value - $mean, 2);
        }, array_column($data, $field))) / count($data);
        return sqrt($variance);
    }

    private function calculateAUC($tp, $fp, $tn, $fn)
    {
        // Hitung AUC
        return ($tp / ($tp + $fn)) * ($tn / ($tn + $fp));
    }

    private function calculateFRate($tp, $fp, $tn, $fn)
    {
        // Hitung F-rate
        return ($fp / ($fp + $tn));
    }

    private function saveConfusionMatrix($confusionMatrix)
    {
        // Simpan confusion matrix ke database
        ConfusionMatrix::create($confusionMatrix);
    }
}
