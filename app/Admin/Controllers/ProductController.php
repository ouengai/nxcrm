<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Product(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('cost');
            $grid->column('price');
            $grid->column('unit');
            $grid->column('state')->display(function ($state) {
                return $state ? '已上线' : '已下线';
            })->dot(
                [
                    0 => 'dark85',
                    1 => 'success',
                ],
                'dark85' // 第二个参数为默认值
            );
            $grid->column('desc')->width('30%')->limit(10);

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');

            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new Product(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('cost');
            $show->field('price');
            $show->field('unit');
            $show->field('state');
            $show->field('desc');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Product(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->currency('cost')->symbol('￥')->required();
            $form->currency('price')->symbol('￥')->required();
            $form->text('unit')->required();
            $form->switch('state','上线状态');
            $form->text('desc');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }

    protected function list(Request $request)
    {
        $provinceId = $request->get('q');

        return Product::find($provinceId)->where('id', $provinceId)->get(['id', DB::raw('price as text')]);
    }
}