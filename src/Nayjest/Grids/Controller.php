<?php
namespace Nayjest\Grids;

use BaseController;
use Carbon\Carbon;
use Globotech\Stats\StatsFinal;
use DB;
use Illuminate\Support\Facades\Input;
use View;
use Nayjest\Common\Controller\ActionViews;
use Globotech\Users\User;

class Controller extends BaseController
{
    use ActionViews;
//    public function getIndex()
//    {
//        var_export(\Input::all());
//        $dp = new EloquentDataProvider(
//            (new User)->newQuery()
//        );
//        $cfg = (new GridConfig)
//            ->setDataProvider($dp)
//            ->setColumns(
//                [
//                    (new FieldConfig)->setName('id'),
//                    (new FieldConfig)
//                        ->setName('login')
//                        ->setIsSortable(true)
//                        ->setSorting(Grid::SORT_ASC),
//                    (new FieldConfig)->setName('first_name'),
//                    (new FieldConfig)->setName('last_name')
//                ]
//            );
//        $grid = new Grid($cfg);
//        $grid = $grid->render();
//        //var_dump($cfg->getDataProvider()->count());
//        //echo $grid->render();
//
//        return $this->view(compact('grid'));
//    }

    public function getIndex()
    {
        //var_dump(Input::all());
        $dp = new EloquentDataProvider(
            (new StatsFinal)->newQuery()
                ->byPartners()
                //->forDates($from, $to)
                ->leftJoin('users','stats_final.partner_id','=','users.id')
                ->addSelect(\DB::raw('users.login as partner_login'))
                ->addSelect(\DB::raw('users.status'))
                ->addSelect(\DB::raw('users.role'))


        );
        $temp_user = new User;
        $cfg = (new GridConfig)
            ->setName('grid')
            ->setDataProvider($dp)
            ->setPageSize(300)
            ->setColumns(
                [
                    (new FieldConfig)
                        ->setName('partner_id')
                        ->setCallback(function($id){
                            $url = route('users.admin.profile', [$id]);
                            return "<a href='$url'>$id</a>";
                        })
                        ->setIsSortable(true)
                        ->addFilter(
                            (new FilterConfig())
                            ->setName('date')
                            ->setLabel('From date')
                            ->setOperator('>=')
                            ->setDefaultValue(Carbon::now()->subWeek()->format('Y-m-d'))
                        ),

                    (new FieldConfig)
                        ->setName('partner_login')
                        ->addFilter(
                            (new FilterConfig())
                                ->setName('date')
                                ->setLabel('To date')
                                ->setOperator('<=')
                                ->setDefaultValue(date('Y-m-d'))
                        ),
                    (new FieldConfig)
                        ->setName('status')
                        //->setLabel('Partner status')
                        ->setCallback(function($status) use ($temp_user){
                            $temp_user->status = $status;
                            return $temp_user->status_label;
                        })
                        ->addFilter(
                            (new SelectFilterConfig)
                                ->setOptions(User::getStatuses())
                        ),
                    (new FieldConfig)
                        ->setName('role')
                        //->setLabel('Partner role')
                        ->setCallback(function($role) use ($temp_user){
                            $temp_user->role = $role;
                            return $temp_user->role_label;
                        })
                        ->addFilter(
                            (new SelectFilterConfig)
                                ->setOptions(User::getRoles())
                        ),
                    (new FieldConfig)->setName('hits')
                        ->setIsSortable(true)
                        ->makeFilter()
                        ->setFilteringFunc(function($val, EloquentDataProvider $dp) {
                            $dp->getBuilder()->having('hits', '>=', $val);
                        })
                        ->setLabel('min')
                        ->getColumn(),
                    (new FieldConfig)->setName('hosts')
                        ->setIsSortable(true)
                        ->makeFilter()
                        ->setFilteringFunc(function($val, EloquentDataProvider $dp) {
                            $dp->getBuilder()->having('hosts', '>=', $val);
                        })
                        ->setLabel('min')
                        ->getColumn(),
                    (new FieldConfig)->setName('regs')
                        ->setIsSortable(true)
                        ->makeFilter()
                        ->setFilteringFunc(function($val, EloquentDataProvider $dp) {
                            $dp->getBuilder()->having('regs', '>=', $val);
                        })
                        ->setLabel('min')
                        ->getColumn(),
                    (new FieldConfig)
                        ->setName('rounds')
                        ->setIsSortable(true)
                        ->makeFilter()
                        ->setFilteringFunc(function($val, EloquentDataProvider $dp) {
                            $dp->getBuilder()->having('rounds', '>=', $val);
                        })
                        ->setLabel('min')
                        ->getColumn(),
                    (new FieldConfig)
                        ->setName('deposits')
                        ->setIsSortable(true)
                        ->setCallback('format_money')
                        ->makeFilter()
                        ->setFilteringFunc(function($val, EloquentDataProvider $dp) {
                            $dp->getBuilder()->having('deposits', '>=', $val*100);
                        })
                        ->setLabel('min, $')
                        ->getColumn(),
                    (new FieldConfig)
                        ->setName('withdraws')
                        ->setIsSortable(true)
                        ->setCallback('format_money')
                        ->makeFilter()
                        ->setFilteringFunc(function($val, EloquentDataProvider $dp) {
                            $dp->getBuilder()->having('withdraws', '<=', -$val*100);
                        })
                        ->setLabel('min, $')
                        ->getColumn(),
                    (new FieldConfig)
                        ->setName('ng')
                        ->setIsSortable(true)
                        ->setCallback('format_money')
                        ->makeFilter()
                        ->setFilteringFunc(function($val, EloquentDataProvider $dp) {
                            $dp->getBuilder()->having('ng', '>=', $val*100);
                        })
                        ->setLabel('min, $')
                        ->getColumn(),
                    (new FieldConfig)
                        ->setName('comppoints')
                        ->setIsSortable(true)
                        ->setCallback('format_money')
                        ->makeFilter()
                        ->setFilteringFunc(function($val, EloquentDataProvider $dp) {
                            $dp->getBuilder()->having('comppoints', '>=', $val*100);
                        })
                        ->setLabel('min, $')
                        ->getColumn(),
                    (new FieldConfig)
                        ->setName('partner_income')
                        ->setIsSortable(true)
                        //->setSorting(Grid::SORT_ASC),
                        ->setCallback('format_money')
                        ->makeFilter()
                        ->setFilteringFunc(function($val, EloquentDataProvider $dp) {
                            $dp->getBuilder()->having('partner_income', '>=', $val*100);
                        })
                        ->setLabel('min, $')
                        ->getColumn()
//                    (new FieldConfig)->setName('first_name'),
//                    (new FieldConfig)->setName('last_name')
                ]
            );
        $grid = new Grid($cfg);
        //var_dump($grid->getConfig()->getDataProvider()->getCollection()[2]);
        $grid = $grid->render();
        //var_dump($cfg->getDataProvider()->count());
        //echo $grid->render();
        return $this->view(compact('grid'));
    }
} 