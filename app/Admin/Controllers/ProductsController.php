<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Product';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product());

        $grid->column('id', __('Id'));
        $grid->column('product_name', __('Product name'));
        $grid->column('product_sku', __('Product sku'));
        $grid->column('product_description', __('Product description'));
        $grid->column('price', __('Price'));
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
        $show = new Show(Product::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('product_name', __('Product name'));
        $show->field('product_sku', __('Product sku'));
        $show->field('product_description', __('Product description'));
        $show->field('price', __('Price'));
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
        $form = new Form(new Product());

        $form->text('product_name', __('Product name'));
        $form->text('product_sku', __('Product sku'));
        $form->textarea('product_description', __('Product description'));
        $form->decimal('price', __('Price'));
        $form->datetime('createdon', __('Createdon'))->default(date('Y-m-d H:i:s'));
        $form->datetime('modifiedon', __('Modifiedon'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
