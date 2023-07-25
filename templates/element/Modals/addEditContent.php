<div class="modal fade" id="addEditContent_mdl" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="listing-modal-1 modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    <div ng-if="!rec.content.id"><?= __('add_content') ?></div>
                    <div ng-if="rec.content.id"><?= __('edit_content') ?></div>
                </h4>
            </div>
            <div class="modal-body">

                <button type="button" id="content_btn" class="hideIt" ng-click="
                    doGet('/admin/contents/index?list=1', 'list', 'contents');
                    rec.content.id>0 ? '' : rec.content = {};
                    doClick('.close');
                    "></button>

                <?php if ($this->request->getQuery('scraping') == 1) { // scraping 
                ?>
                    <div>added:{{rec.content.success+' / scanned:'+rec.content.inc}}</div>
                    <input type="text" ng-model="rec.content.from" />
                    <input type="text" ng-model="rec.content.to" />
                    <button ng-click="loopInUrls(rec.content.from, rec.content.to)"><?= __('scrape') ?></button>
                <?php } ?>

                <form class="row" id="content_form" ng-submit="
                    doSave(rec.content, 'content', 'contents', '#content_btn', '#content_preloader'); ">

                    <?php if ($this->request->getQuery('scraping') == 1) { // scraping 
                    ?>
                        <div class="col-md-12" ng-if="!rec.content.id>0">
                            <label><?= __('content_src') ?></label>
                            <div class="div">
                                <?= $this->Form->control('content_src', [
                                    'class' => 'form-control has-feedback-left',
                                    'label' => false,
                                    'type' => 'text',
                                    'ng-model' => 'rec.content.content_src',
                                    'placeholder' => __('content_src'),
                                ]) ?>
                                <span class="fa fa-globe form-control-feedback left" aria-hidden="true"></span>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="col-md-7">
                        <label><?= __('language_id') ?></label>
                        <div class="div">
                            <?= $this->Form->control('language_id', [
                                'class' => 'form-control has-feedback-left',
                                'label' => false,
                                'options' => $this->Do->lcl($this->Do->get('langs')),
                                'type' => 'select',
                                'ng-model' => 'rec.content.language_id',
                                'placeholder' => __('language_id'),
                            ]) ?>
                            <span class="fa fa-globe form-control-feedback left" aria-hidden="true"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label><?= __('content_desc') ?></label>
                        <div class="div">
                            <?= $this->Form->control('content_desc', [
                                'class' => 'form-control',
                                'label' => false,
                                'type' => 'textarea',
                                'ng-model' => 'rec.content.content_desc',
                                'placeholder' => __('content_desc'),
                                'ckeditor'=>'ckoptions',
                            ]) ?>
                        </div>
                    </div>

                    <div class="col-md-12 col-12 col-sm-12" id="accordion_features">
                        <div class="row">
                            <?php foreach ($this->Do->get('features_parents') as $k => $group) { ?>

                                <div class="col-md-12" data-toggle="collapse" data-target="#group_<?= $k ?>">
                                    <b class="btn"><?= __($group) ?></b>
                                </div>

                                <div id="group_<?= $k ?>" class="col-md-12 collapse" data-parent="#accordion_features">
                                    <?php foreach ($this->Do->get('features')[$k] as $k2 => $feature) { ?>

                                        <div class="col-lg-3 col-sm-4 col-6 ">
                                            <label class="mycheckbox">
                                                <?= $this->Form->control($feature, [
                                                    'label' => false,
                                                    'type' => 'checkbox',
                                                    'ng-model' => 'rec.content.features_ids[' . $k2 . ']',
                                                    'ng-value' => $k2,
                                                    'templates' => [
                                                        'inputContainer' => '{{content}}'
                                                    ]
                                                ]) ?>
                                                <span></span>&nbsp;<span class="chkbox_text"><?= __($feature) ?></span>
                                            </label>
                                        </div>

                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label><?= __('seo_keywords') ?></label>
                        <?=$this->element('tagInput-ng', ['ng'=>'rec.content.seo_keywords'])?>
                    </div>
                    
                    <div class="col-lg-12 mb-3"></div>

                    <h3 class="col-sm-12"><?=__('specs')?></h3>

                    <div class="col-sm-6" ng-repeat="spec in rec.content.specs track by $index">
                        <label>{{spec.spec_name}}</label>
                        <div class="div">
                            <?= $this->Form->control('language_id', [
                                'class' => 'form-control has-feedback-left',
                                'label' => false,
                                'options' => $this->Do->lcl($this->Do->get('langs')),
                                'type' => 'text',
                                'ng-model' => 'rec.content.specs[$index].spec_value',
                            ]) ?>
                            <span class="fa fa-info form-control-feedback left" aria-hidden="true"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="div">
                            <label class="mycheckbox">
                                <?= $this->Form->control($feature, [
                                    'label' => false,
                                    'type' => 'checkbox',
                                    'ng-model' => 'rec.content.content_istranslated',
                                    'ng-true-value' => "1",
                                    'ng-false-value' => "0",
                                    'templates' => [
                                        'inputContainer' => '{{content}}'
                                    ]
                                ]) ?>
                                <span></span>&nbsp;&nbsp;<span class="chkbox_text"><?= __('content_istranslated') ?></span>
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3"></div>

                    <div class="col-md-12 ">
                        <button type="submit" id="content_preloader" class="btn btn-info"><span></span> <i class="fa fa-save"></i> <?= __('save') ?></button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>