<?php

    $params = http_build_query( $this->request->getQuery() );
?>

<div class="right_col" role="main" ng-init="
        doGet('/admin/contents/index?list=1&<?=$params?>', 'list', 'contents');
    ">
    <div class="">
        <div class="page-title">
            <div class=" col-6 col-sm-6 col-md-6 side_div1">
                <h3><?=__('contents_list')?></h3>
            </div>
            <div class=" col-6 col-sm-6 col-md-6 side_div2" >
                <span class="icn">
                    <a href ng-click="newEntity('content')" data-toggle="modal" data-target="#addEditContent_mdl" data-dismiss="modal"  class="btn btn-info">
                        <span class="fa fa-plus"></span> <span class="hideMob"><?=__('add_content')?></span>
                    </a>
                </span>
            </div>
        </div>

        <div class="clearfix"></div>

        
        <div class="row">
            <div class="col-12">
                <div class="x_panel">

                    <div id="main_preloader" class="preloader">
                        <div>
                            <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>
                        </div>
                        <div><?=__('please_wait')?></div>
                    </div>

                    <div class="x_title">
                        <h2><b><?=__('contents_list')?></b> 
                            <span> <?=__('show').' '.__('from')?>
                                {{ paging.start  }} <?=__('to')?> 
                                {{ paging.end }} <?=__('of')?> {{ paging.count }} </span></h2>
                        
                        <?php if(in_array($authUser['user_role'], ['admin.root', 'admin.admin'])){?>
                        <ul class="nav navbar-right panel_toolbox">
                            <!-- <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li> -->
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                    aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <div class="dropdown-menu  <?= $currlang!='ar' ? 'dropdown-menu-right' : ''?>">
                                    <a href ng-click="multiHandle('/admin/contents/enable/1')" class="dropdown-item">
                                        <i class="fa fa-check"></i> <?=__('enable_selected')?>
                                    </a>
                                    <a href ng-click="multiHandle('/admin/contents/enable/0')" class="dropdown-item">
                                        <i class="fa fa-times"></i> <?=__('disable_selected')?>
                                    </a>
                                    <a href ng-click="multiHandle('/admin/contents/delete')" class="dropdown-item">
                                        <i class="fa fa-trash"></i> <?=__('delete_selected')?>
                                    </a>
                                </div>
                            </li>
                            <!-- <li><a class="close-link"><i class="fa fa-close"></i></a> 
                            </li>-->
                        </ul>
                        <?php }?>
                        
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">

                        <div class="grid ">

                            <div class="grid_header row">

                                <?php if(in_array($authUser['user_role'], ['admin.root', 'admin.admin', 'admin.supervisor'])){?>
                                <div class="col-sm-1 col column-title">
                                    <?=$this->element('colActions', ['url'=>'contents/index/', 'col'=>'id'])?>
                                    <label class="mycheckbox">
                                        <input type="checkbox" ng-click="chkAll('.chkb', !selectAll)" ng-model="selectAll">
                                        <span></span> 
                                        <?=__('id')?> 
                                    </label> 
                                </div>
                                <?php }?>
                                
                                <div class="col-sm-5 col column-title">
                                    <?=$this->element('colActions', ['url'=>'contents/index/', 'col'=>'content_title', 'search'=>'content_title'])?> 
                                    <?=__('content_title')?> </div>

                                <div class="col-sm-2 col column-title">
                                    <?=$this->element('colActions', ['url'=>'contents/index/', 'col'=>'stat_created'])?> 
                                    <?=__('stat_created')?> </div>

                                <div class="col-sm-2 col column-title">
                                    <?=$this->element('colActions', ['url'=>'contents/index/', 'col'=>'rec_state', 'filter'=>$this->Do->lcl( $this->Do->get('bool'))])?> 
                                    <?=__('rec_state')?> </div>

                                <div class="col-sm-2 col column-title hideMob"><span
                                        class="nobr"><?=__('action')?></span>
                                </div>
                            </div>
                            
                            <div class="grid_row row" ng-repeat="itm in lists.contents">

                                <?php if(in_array($authUser['user_role'], ['admin.root', 'admin.admin', 'admin.supervisor'])){?>
                                <div class="col-sm-1 hideMobSm grid_header">
                                    <label class="mycheckbox chkb">
                                        <input type="checkbox" ng-model="selected[itm.id]" ng-value="{{itm.id}}">
                                        <span></span> {{ itm.id }}
                                    </label>
                                </div>

                                <div class="col-4 hideWeb grid_header">
                                    <?=__('id')?> 
                                    <label class="mycheckbox chkb">
                                        <input type="checkbox" ng-model="selected[itm.id]" ng-value="{{itm.id}}">
                                        <span></span>
                                    </label>
                                </div>
                                
                                <div class="col-md-1 col-8 hideWeb">{{ itm.id }}</div>
                                <?php }?>

                                <div class="col-4 hideWeb grid_header"><?=__('content_title')?></div>
                                <div class="col-md-5 col-8">{{ itm.content_title }}</div>

                                <div class="col-4 hideWeb grid_header"><?=__('stat_created')?></div>
                                <div class="col-md-2 col-8">{{ itm.stat_created }} </div>

                                <div class="col-4 hideWeb grid_header"><?=__('rec_state')?></div>
                                <div class="col-md-2 col-8" ng-bind-html="DtSetter('bool2', itm.rec_state)"></div>

                                <div class="col-4 hideWeb grid_header"><?=__('actions')?></div>
                                <div class="col-md-2 col-8 action">
                                    <a href="javascript:void(0);" class="inline-btn"
                                        ng-click="
                                            doGet('/admin/contents?id='+itm.id, 'rec', 'content');
                                            openModal('#viewContent_mdl');
                                            ">
                                        <i class="fa fa-eye"></i> <?=__('view')?>
                                    </a> &nbsp; 
                                    <a href="javascript:void(0);" 
                                        ng-click="
                                            doGet('/admin/contents?id='+itm.id, 'rec', 'content');
                                            openModal('#addEditContent_mdl');
                                            " class="inline-btn">
                                        <i class="fa fa-pencil"></i> <?=__('edit')?>
                                    </a>
                                </div>
                            </div>

                        </div>
                        
                        <?php echo $this->element('paginator-ng')?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->element('Modals/addEditContent')?>
<?php echo $this->element('Modals/viewContent')?>
