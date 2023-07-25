<?php
$ctrl = $this->request->getParam('controller') == 'Properties' ? 'property' : 'project';
$prefix = $this->request->getParam('controller') == 'Properties' ? 'PROP' : 'PROJ';
?>

<div class="modal fade modal-right" id="search_mdl" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="listing-modal-1 modal-dialog modal-lg aside-modal">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">
                    <?= __('search_and_filter') ?>
                </h2>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <form method="post" novalidate="novalidate" id="search_form" class="row" ng-submit=" rec.search.page = 1; doSearch(); ">

                                <?php // GENERAL SEARCH 
                                ?>
                                <div class="col-sm-12">
                                    <h5 data-toggle="collapse" data-target="#search_sec" class="sec_header"> <?= __('general_search') ?> </h5>
                                </div>
                                <div id="search_sec" class="collapse show col-12" data-parent="#search_form">
                                    <div class="row">

                                        <?php if ($ctrl == 'property') { ?>

                                            <div class="mb-3 col-12">
                                                <label><?= __('project_id') ?></label>
                                                <div class="div">
                                                    <select class="form-control selectpicker" ng-model="rec.search.project_id" data-live-search="true" multiple="true" multi-select="1" data-done-button="true" actn="doClick('#submit_btn')" data-size="6">
                                                        <option ng-value="key" ng-repeat="(key, itm) in lists.projects_list">{{itm}}</option>
                                                    </select>
                                                    <!-- <span class="fa fa-building-o form-control-feedback left" aria-hidden="true"></span> -->
                                                </div>
                                            </div>

                                            <div class="mb-3 col-12">
                                                <label><?= __('update_status') ?></label>
                                                <div class="div">

                                                    <label class="myradiobtn redText">
                                                        <input type="radio" ng-model="rec.search.stat_updated" ng-change="doClick('#submit_btn')" value="0">
                                                        <span></span> <?= __('outdated') ?> &nbsp;
                                                    </label>
                                                    <label class="myradiobtn">
                                                        <input type="radio" ng-model="rec.search.stat_updated" ng-change="doClick('#submit_btn')" value="1">
                                                        <span></span> <?= __('updated') ?> &nbsp;
                                                    </label>

                                                </div>
                                            </div>
                                        <?php } ?>

                                        <div class="mb-2 col-sm-8">
                                            <label><?= __('search_keyword') ?></label>
                                            <div class="div">
                                                <?= $this->Form->control('keyword', [
                                                    'class' => 'form-control has-feedback-left', 'label' => false,
                                                    'type' => 'text', 'placeholder' => __('input_keyword'),
                                                    'ng-model' => 'rec.search.keyword',
                                                ]) ?>
                                                <span class="fa fa-search form-control-feedback left"></span>
                                                <button ng-click="doClick('#submit_btn')" class="onfly_btn"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>

                                        <?php if ($ctrl == 'property') { ?>

                                        <div class=" col-12">
                                            <label class="myradiobtn" ng-repeat="(k, itm) in DtSetter('currencies', 'list')">
                                                <input type="radio" ng-model="rec.search.property_currency" value="{{k}}">
                                                <span></span> {{DtSetter('currencies', itm)}} &nbsp;
                                            </label>
                                        </div>
                                            
                                        <div class="mb-4 col-12">
                                            <label><?= __('property_price') ?></label>
                                            <div class="fromToDiv">
                                                <b><?= __('from') ?></b>
                                                <span>
                                                    <input type="text" only-numbers="" config="{group:'.',decimal:'.', decimalSize: 0,indentation:''}" mask-currency="false" 
                                                        ng-model="rec.search.property_price[0]"> 
                                                </span>
                                                <b><?= __('to') ?></b>
                                                <span>
                                                    <input type="text" only-numbers="" config="{group:'.',decimal:'.', decimalSize: 0,indentation:''}" mask-currency="false" 
                                                        ng-model="rec.search.property_price[1]"> 
                                                </span>
                                                <span>
                                                    <button ng-click="doClick('#submit_btn')" class="small-btn"><i class="fa fa-search"></i></button>
                                                </span>
                                            </div>
                                        </div>

                                        <?php }else{ ?>
                                            
                                        <div class="mb-4  col-sm-8">
                                            <label><?= __('budget') ?></label>
                                            <div class="div">
                                                <?= $this->Form->control('budget', [
                                                    'class' => 'form-control has-feedback-left', 'label' => false,
                                                    'type' => 'text', 'placeholder' => __('budget'),
                                                    'ng-model' => 'rec.search.property_price[0]',
                                                    'only-numbers'=>'',
                                                    'config' => "{group:'.',decimal:'.', decimalSize: 0,indentation:''}",
                                                    'mask-currency' => 'false'
                                                ]) ?>
                                                <span class="fa fa-{{DtSetter('currencies', '<?=$currCurrency?>').toLowerCase()}} form-control-feedback left"></span>
                                                <button ng-click="doClick('#submit_btn')" class="onfly_btn"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                        
                                        <?php } ?>
                                    </div>
                                </div>


                                <?php // ADDRESS 
                                ?>
                                <div class="col-sm-12">
                                    <h5 data-toggle="collapse" data-target="#address_sec" class="sec_header"> <?= __('address') ?></h5>
                                </div>
                                <div id="address_sec" class="collapse col-12" data-parent="#search_form">
                                    <div class="row mb-4">
                                        <?php /*?>
                                        <div class=" col-sm-4">
                                            <label><?=__('adrs_country')?></label>
                                            <div class="div">
                                                <?=$this->Form->control("adrs_country", [
                                                    "class"=>"form-control has-feedback-left", "type"=>"text",
                                                    "empty"=>__("select_language"), "label"=>false,
                                                    "ng-model"=>"rec.search.adrs_country" , "placeholder"=>__("adrs_country"),
                                                    'ng-change'=>"findAddress('adrs_country')",
                                                    'ng-focus'=>"findAddress('adrs_country')",
                                                    "name"=>false, "autocomplete"=>"off",
                                                ])?>
                                                <span class="fa fa-map-marker form-control-feedback left"></span>
                                            </div>
                                            <div class="onfly_menu" click-outside="lists.addresses['adrs_country']=[];">
                                                <a href ng-repeat="itm in lists.addresses['adrs_country']" 
                                                    ng-click="
                                                        rec.search.adrs_country = itm.adrs_country; 
                                                        doClick('#submit_btn');
                                                        lists.addresses['adrs_country']=[];
                                                        " >{{itm.adrs_country}}</a>
                                            </div>
                                        </div>
                                        <?php */ ?>
                                        <div class=" col-sm-4">
                                            <label><?= __('adrs_city') ?></label>
                                            <div class="div">
                                                <?= $this->Form->control("adrs_city", [
                                                    "class" => "form-control has-feedback-left", "type" => "text",
                                                    "empty" => __("select_language"), "label" => false,
                                                    "ng-model" => "rec.search.adrs_city", "placeholder" => __("adrs_city"),
                                                    'ng-change' => "findAddress('adrs_city')",
                                                    'ng-focus' => "findAddress('adrs_city')",
                                                    "name" => false, "autocomplete" => "off",
                                                ]) ?>
                                                <span class="fa fa-map-marker form-control-feedback left"></span>
                                            </div>
                                            <div class="onfly_menu" click-outside="lists.addresses['adrs_city']=[];">
                                                <a href ng-repeat="itm in lists.addresses['adrs_city']" ng-click="
                                                        rec.search.adrs_city = itm.adrs_city; 
                                                        doClick('#submit_btn');
                                                        lists.addresses['adrs_city']=[];
                                                        ">{{itm.adrs_city}}</a>
                                            </div>
                                        </div>
                                        <div class=" col-sm-4">
                                            <label><?= __('adrs_region') ?></label>
                                            <div class="div">
                                                <?= $this->Form->control("adrs_region", [
                                                    "class" => "form-control has-feedback-left", "type" => "text",
                                                    "empty" => __("select_language"), "label" => false,
                                                    "ng-model" => "rec.search.adrs_region", "placeholder" => __("adrs_region"),
                                                    'ng-change' => "findAddress('adrs_region')",
                                                    'ng-focus' => "findAddress('adrs_region')",
                                                    "name" => false, "autocomplete" => "off",
                                                ]) ?>
                                                <span class="fa fa-map-marker form-control-feedback left"></span>
                                            </div>
                                            <div class="onfly_menu" click-outside="lists.addresses['adrs_region']=[];">
                                                <a href ng-repeat="itm in lists.addresses['adrs_region']" ng-click="
                                                        rec.search.adrs_region = itm.adrs_region; 
                                                        doClick('#submit_btn');
                                                        lists.addresses['adrs_region']=[];
                                                        ">{{itm.adrs_region}}</a>
                                            </div>
                                        </div>

                                        <div class=" col-sm-4">
                                            <label><?= __('adrs_district') ?></label>
                                            <div class="div">
                                                <?= $this->Form->control("adrs_district", [
                                                    "class" => "form-control has-feedback-left", "type" => "text",
                                                    "empty" => __("select_language"), "label" => false,
                                                    "ng-model" => "rec.search.adrs_district", "placeholder" => __("adrs_district"),
                                                    'ng-change' => "findAddress('adrs_district')",
                                                    'ng-focus' => "findAddress('adrs_district')",
                                                    "name" => false, "autocomplete" => "off",
                                                ]) ?>
                                                <span class="fa fa-map-marker form-control-feedback left"></span>
                                            </div>

                                            <div class="onfly_menu" click-outside="lists.addresses['adrs_district']=[];">
                                                <a href ng-repeat="itm in lists.addresses['adrs_district']" ng-click="
                                                        rec.search.adrs_district = itm.adrs_district; 
                                                        doClick('#submit_btn');
                                                        lists.addresses['adrs_district']=[];
                                                    ">{{itm.adrs_district}}
                                                </a>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <?php // (USP) UNIQUE SELL POINT 
                                ?>
                                <?php /* if($ctrl == 'property'){?>
                                <div class="col-sm-12">
                                    <h5 data-toggle="collapse" data-target="#usps_sec" class="sec_header"> <?=__('usp')?> </h5>
                                </div>
                                <div id="usps_sec" class="collapse col-12" data-parent="#search_form">
                                    <div class="row">

                                        <?php foreach($this->Do->get('USP_CATEGORIES') as $k=>$usp){ ?>

                                        <div class="col-lg-3 col-sm-4 col-6 ">
                                            <label class="mycheckbox">
                                                <?=$this->Form->control($usp, [
                                                    'label'=>false,
                                                    'type'=>'checkbox',
                                                    'ng-model'=>'rec.search.property_usp['.$k.']',
                                                    'ng-value'=>$k,
                                                    'templates' => [ 'inputContainer' => '{{content}}' ],
                                                    'ng-change'=>"doClick('#submit_btn')"
                                                ])?>
                                                <span></span>&nbsp;<?=__($usp)?> 
                                            </label>
                                        </div>

                                        <?php }?>

                                    </div>
                                </div>
                                <?php } */ ?>

                                <?php // SPECIFICATIONS 
                                ?>
                                <div class="col-sm-12">
                                    <h5 data-toggle="collapse" data-target="#specs_sec" class="sec_header"> <?= __('specs') ?> </h5>
                                </div>
                                <div id="specs_sec" class="collapse col-12" data-parent="#search_form">
                                    <div class="row">
                                        <?php
                                        if ($ctrl == 'property') {
                                            $ends = ['param_monthlytax' => 9999, 'param_deposit' => 9999, 'param_grossspace' => 9999, 'param_netspace' => 9999, 'param_titledeed' => 9999];
                                            $steps = ['param_monthlytax' => 500, 'param_deposit' => 500, 'param_grossspace' => 5, 'param_netspace' => 5, 'param_titledeed' => 500];
                                            $units = ['param_monthlytax' => __('tl'), 'param_deposit' => __('tl'), 'param_grossspace' => __('m2'), 'param_netspace' => __('m2'), 'param_titledeed' => __('tl')];
                                        }
                                        if ($ctrl == 'project') {
                                            $ends = ['param_space' => 9999, 'param_totalunits' => 9999, 'param_blocks' => 9999, 'param_bldfloors' => 60, 'param_residential_units' => 60, 'param_commercial_units' => 60, 'param_units_size_range' => 9999];
                                            $steps = ['param_space' => 500, 'param_totalunits' => 100, 'param_blocks' => 100, 'param_bldfloors' => 1, 'param_residential_units' => 1, 'param_commercial_units' => 1, 'param_units_size_range' => 10];
                                            $units = ['param_space' => __('m2'), 'param_totalunits' => __('unit'), 'param_blocks' => __('unit'), 'param_bldfloors' => __('floor'), 'param_residential_units' => __('unit'), 'param_commercial_units' => __('unit'), 'param_units_size_range' => __('m2')];
                                        }
                                        foreach ($this->Do->cat($prefix . '_SPECS.main') as $k => $spec) {
                                            if ($spec == 'param_deliverdate') {
                                                continue;
                                            }
                                            $list = $this->Do->cat($prefix . '_SPECS.' . $k);
                                        ?>

                                            <?php if (!is_array($list)) { // slider items
                                            ?>

                                                <div class=" mb-4 col-lg-4 col-md-6">
                                                    <label><?= __($spec) ?></label>
                                                    <div class="fromToDiv">

                                                        <?php if (in_array($spec, ['param_netspace', 'param_grossspace'])) { // only one handler
                                                        ?>
                                                            <b><?= __('min') ?></b>
                                                            <span><input type="text" only-numbers="" config="{group:'.',decimal:'.', decimalSize: 0,indentation:''}" mask-currency="false" ng-model="rec.search.<?= $spec ?>[0]"> </span>
                                                            <button ng-click="doClick('#submit_btn')" class="small-btn"><i class="fa fa-search"></i></button></span>

                                                        <?php } else { ?>
                                                            <b><?= __('from') ?></b>
                                                            <span><input type="text" only-numbers="" config="{group:'.',decimal:'.', decimalSize: 0,indentation:''}" mask-currency="false" ng-model="rec.search.<?= $spec ?>[0]"> </span>
                                                            <b><?= __('to') ?></b>
                                                            <span><input type="text" only-numbers="" config="{group:'.',decimal:'.', decimalSize: 0,indentation:''}" mask-currency="false" ng-model="rec.search.<?= $spec ?>[1]"> </span>
                                                            <button ng-click="doClick('#submit_btn')" class="small-btn"><i class="fa fa-search"></i></button></span>

                                                        <?php } ?>
                                                    </div>
                                                </div>

                                            <?php } else { ?>

                                                <?php if (count($list) < 2) { // checkbox items 
                                                ?>

                                                    <div class="col-lg-4 col-md-6  form-group has-feedback">
                                                        <label><?= __($spec) ?></label>
                                                        <div class="div specsRadioBtn">
                                                            <label class="myradiobtn">
                                                                <input type="radio" ng-change="doClick('#submit_btn')" ng-model="rec.search.<?= $spec ?>" name="<?= $spec ?>" value="1" /> <span></span> <?= __('yes') ?>
                                                            </label>&nbsp;
                                                            <label class="myradiobtn">
                                                                <input type="radio" ng-change="doClick('#submit_btn')" ng-model="rec.search.<?= $spec ?>" name="<?= $spec ?>" value="0" /> <span></span> <?= __('no') ?>
                                                            </label>
                                                        </div>
                                                    </div>

                                                <?php } else { // selectable menus items 
                                                ?>

                                                    <div class="col-lg-4 col-md-6  form-group has-feedback">
                                                        <label><?= __($spec) ?></label>
                                                        <div class="div">
                                                            <?= $this->Form->control($spec, [
                                                                'class' => 'form-control', //has-feedback-left
                                                                'label' => false,
                                                                'type' => 'select',
                                                                'multiple' => count($list) > 2 ? true : false,
                                                                'multi-select' => true,
                                                                'empty' => true,
                                                                'ng-model' => 'rec.search.' . $spec,
                                                                'options' => $this->Do->lcl($list),
                                                                'actn' => "doClick('#submit_btn')",
                                                                'data-done-button' => true,
                                                                // 'data-live-search'=>true,
                                                            ]) ?>
                                                            <!-- <span class="fa fa-info-circle form-control-feedback left" aria-hidden="true"></span> -->
                                                        </div>
                                                    </div>

                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <?php // FEATURES 
                                ?>
                                <div class="col-sm-12">
                                    <h5 data-toggle="collapse" data-target="#features_sec" class="sec_header"> <?= __($ctrl == 'property' ? 'features' : 'facilities') ?> </h5>
                                </div>
                                <div id="features_sec" class="collapse col-12" data-parent="#search_form">
                                    <div class="row">

                                        <?php foreach ($this->Do->cat($prefix . '_FEATURES.main') as $k => $group) { ?>

                                            <div class="col-12">
                                                <b><?= __($group) ?></b>
                                            </div>

                                            <?php foreach ($this->Do->cat($prefix . '_FEATURES.' . $k) as $k2 => $feature) { ?>

                                                <div class="col-lg-3 col-sm-4 col-6 ">
                                                    <label class="mycheckbox">
                                                        <?= $this->Form->control($feature, [
                                                            'label' => false,
                                                            'type' => 'checkbox',
                                                            'ng-model' => 'rec.search.features_ids[' . $k2 . ']',
                                                            'ng-value' => $k2,
                                                            'templates' => ['inputContainer' => '{{content}}'],
                                                            'ng-change' => "doClick('#submit_btn')"
                                                        ]) ?>
                                                        <span></span>&nbsp;<span class="chkbox_text"><?= __($feature) ?></span>
                                                    </label>
                                                </div>

                                            <?php } ?>

                                            <div class="clearfix col-12 mb-3"></div>
                                        <?php } ?>

                                    </div>
                                </div>

                                <button class="hideIt" type="submit" id="submit_btn"></button>

                            </form>
                        </div>

                        <div class="col-sm-12 mb-5"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>