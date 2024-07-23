<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Encore\Admin\Facades\Admin;

class OrdersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Order';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());

        $grid->column('id', __('Id'));
        $grid->column('no_order', __('No order'));
        $grid->column('order_date', __('Order date'));
        $grid->column('total_price', __('Total price'));
        $grid->column('total_disc', __('Total disc'));
        $grid->column('sub_total_price', __('Sub total price'));
        $grid->column('createdon', __('Createdon'));
        $grid->column('modifiedon', __('Modifiedon'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('no_order', __('No order'));
        $show->field('order_date', __('Order date'));
        $show->field('total_price', __('Total price'));
        $show->field('total_disc', __('Total disc'));
        $show->field('sub_total_price', __('Sub total price'));
        $show->field('createdon', __('Createdon'));
        $show->field('modifiedon', __('Modifiedon'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order());

        // Fetch all products with their names and prices
        $products = Product::all(['id', 'product_name', 'price'])->keyBy('id')->toArray();

        // Send product data to JavaScript as a global variable
        Admin::script($this->generateProductScript($products));

        // Order fields
        $form->text('no_order', __('No order'))->readonly()->attribute('id', 'no_order');
        $form->date('order_date', __('Order date'))->default(date('Y-m-d'))->attribute('id', 'order_date');
        $form->decimal('total_price', __('Total price'))->default(0.00)->readonly()->attribute('id', 'total_price');
        $form->decimal('total_disc', __('Total disc'))->default(0.00)->attribute('id', 'total_disc');
        $form->decimal('sub_total_price', __('Sub total price'))->default(0.00)->readonly()->attribute('id', 'sub_total_price');

        // OrderDetail nested form
        $form->hasMany('orderDetails', function (Form\NestedForm $form) use ($products) {
            $productOptions = array_map(function($product) {
                return $product['product_name'];
            }, $products);

            $form->select('product_id', __('Product name'))->options($productOptions)->attribute('class', 'product-select');
            $form->number('product_qty', __('Product qty'))->default(1)->attribute('class', 'product-qty');
            $form->decimal('total', __('Total'))->default(0.00)->readonly()->attribute('class', 'detail-total');
        });

        $form->saving(function (Form $form) {
            $subTotalPrice = 0;

            foreach ($form->orderDetails as $detail) {
                $product = Product::find($detail['product_id']);
                $detail['total'] = $product->price * $detail['product_qty'];
                $subTotalPrice += $detail['total'];
            }

            $form->sub_total_price = $subTotalPrice;
            $form->total_price = $subTotalPrice - $form->total_disc;
        });

        return $form;
    }

    protected function generateProductScript($products)
    {
        $productJson = json_encode($products);

        return <<<SCRIPT
                var products = $productJson;
                console.log('Products:', products);

                $(document).on('change', '.orderDetails.product_id, .product-qty', function() {
                    console.log('Product or Quantity changed');
                    updateOrderDetails();
                });

                $(document).on('change', '#total_disc', function() {
                    console.log('Discount changed');
                    updateOrderTotal();
                });

                function updateOrderDetails() {
                    console.log('Updating order details...');
                    var subTotalPrice = 0;

                    $('.has-many-orderDetails-forms .has-many-orderDetails-form.fields-group').each(function(index, element) {
                        var productId = $('.orderDetails.product_id').val();
                        console.log('Product ID:', productId);

                        if (productId && products.hasOwnProperty(productId)) {
                            var productQty = parseInt($(this).find('.product-qty').val()) || 0;
                            var product = products[productId];

                            var totalPrice = product.price * productQty;
                            $(this).find('.detail-total').val(totalPrice.toFixed(2));
                            subTotalPrice += totalPrice;
                        } else {
                            console.log('Product not found or invalid for ID:', productId);
                            // Handle case where product is not found or productId is invalid
                        }
                    });

                    $('#sub_total_price').val(subTotalPrice.toFixed(2));
                    updateOrderTotal();
                }

                function updateOrderTotal() {
                    console.log('Updating order total...');
                    var subTotalPrice = parseFloat($('#sub_total_price').val()) || 0;
                    var totalDisc = parseFloat($('#total_disc').val()) || 0;
                    var totalPrice = subTotalPrice - totalDisc;
                    $('#total_price').val(totalPrice.toFixed(2));
                }

                // Ensure the function runs initially
                updateOrderDetails();
        SCRIPT;
    }
}
