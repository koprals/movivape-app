<?php

namespace App\Admin\Controllers;

use App\Models\OrderDetail;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderDetailsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'OrderDetail';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OrderDetail());

        $grid->column('id', __('Id'));
        $grid->column('order_id', __('Order id'));
        $grid->column('product_id', __('Product id'));
        $grid->column('product_qty', __('Product qty'));
        $grid->column('total_price', __('Total price'));
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
        $show = new Show(OrderDetail::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('order_id', __('Order id'));
        $show->field('product_id', __('Product id'));
        $show->field('product_qty', __('Product qty'));
        $show->field('total_price', __('Total price'));
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
        $form = new Form(new OrderDetail());

        $form->number('order_id', __('Order id'));
        $form->number('product_id', __('Product id'));
        $form->number('product_qty', __('Product qty'));
        $form->decimal('total_price', __('Total price'))->default(0.00);
        $form->datetime('createdon', __('Createdon'))->default(date('Y-m-d H:i:s'));
        $form->datetime('modifiedon', __('Modifiedon'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
