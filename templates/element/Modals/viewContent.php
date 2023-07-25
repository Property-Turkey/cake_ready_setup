

<div class="modal fade" id="viewContent_mdl" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="listing-modal-1 modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    <?= __('view') ?>
                </h4>
            </div>

            <div class="modal-body row">
                <div class="col-md-12 col-sm-12">
                    <div class="view_page">
                        <div class="grid">

                            <div class="grid_row row">
                                <h4 class="col-12">
                                    <b>{{ rec.content.content_title }}</b>
                                </h4>
                            </div>

                            <div class="grid_row row">
                                <div class="col-md-3 grid_header2"><?=__('imgs')?></div>
                                <div class="col-md-9 notwrapped">
                                    <span class="thumb-img ng-scope" 
                                        ng-repeat="img in rec.content.imgs track by $index">
                                        <img ng-src="<?=$app_folder?>/img{{langPrefix(rec.content.language_id)}}/contents_photos/thumb/{{img.spec_value}}" style="height:70px" 
                                            show-img="" >
                                    </span>
                                </div>
                            </div>

                            <div class="grid_row row">
                                <div class="col-md-3 grid_header2"><?=__('flooplans')?></div>
                                <div class="col-md-9 notwrapped">
                                    <span class="thumb-img ng-scope" 
                                        ng-repeat="fp in rec.content.floorplans track by $index">
                                        <img ng-src="<?=$app_folder?>/img{{langPrefix(rec.content.language_id)}}/contents_photos/thumb/{{fp.spec_value}}" style="height:70px" 
                                            show-img="" >
                                    </span>
                                </div>
                            </div>

                            <div class="grid_row row">
                                <div class="col-md-3 grid_header2"><?=__('content_desc')?></div>
                                <div class="col-md-9 notwrapped" ng-bind-html="rec.content.content_desc"></div>
                            </div>
                            
                            <div class="grid_row row">
                                <div class="col-md-3 grid_header2"><?=__('features_ids')?></div>
                                <div class="col-md-9 notwrapped"> <span class="badge badge-warning" 
                                    ng-repeat="(k, itm) in rec.content.features_ids track by $index">{{DtSetter('all_features', k)}}</span> 
                                </div>
                            </div>
                            
                            <div class="grid_row row">
                                <div class="col-md-3 grid_header2"><?=__('seo_keywords')?></div>
                                <div class="col-md-9 notwrapped"> <span class="badge badge-info" 
                                    ng-repeat="(k, itm) in rec.content.seo_keywords track by $index">{{itm}}</span> 
                                </div>
                            </div>
                            
                            <div class="grid_row row">
                                <div class="col-md-3 grid_header2"><?=__('stat_created')?> / <?=__('stat_updated')?></div>
                                <div class="col-md-9 notwrapped">
                                    <b>{{rec.content.stat_created}}</b> / <b>{{rec.content.stat_updated}}</b>
                                </div>
                            </div>
                            
                            <div class="grid_row row">
                                <div class="col-md-3 grid_header2"><?=__('stat_views')?> / <?=__('stat_shares')?></div>
                                <div class="col-md-9 notwrapped">
                                    <b>{{rec.content.stat_views}}</b> / <b>{{rec.content.stat_shares}}</b>
                                </div>
                            </div>

                            <div class="grid_row row">
                                <h2 class="col-md-3 grid_header2"><?=__('specs')?></h2>
                                <div class="col-md-9 notwrapped">
                                    <div ng-repeat="spec in rec.content.specs" class="row"
                                        ng-if="'img,floorplan'.indexOf( spec.spec_name ) < 0">
                                        <div class="col-sm-4"><b>{{spec.spec_name}}</b>:</div>
                                        <div class="col-sm-8">{{spec.spec_configs.type}} {{nFormat( spec.spec_value )}} {{spec.spec_configs.currency}}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid_row row">
                                <div class="col-md-3 grid_header2"><?=__('content_src')?></div>
                                <div class="col-md-9 grayText notwrapped">{{rec.content.content_src}}</div>
                            </div>

                            <div class="grid_row row">
                                <div class="col-md-3 grid_header2"><?=__('translate_to')?></div>
                                <div class="col-md-9 grayText notwrapped">

                                    <button ng-repeat="lang in DtSetter('langs', 'list')" ng-click="
                                        doSave(rec.content, 'content', 'contents', false, false, '?transTo='+lang); 
                                        "> {{rec.content.id+' to '+lang}}</button>
                                        <br>
                                        
                                    <button ng-repeat="lang in DtSetter('langs', 'list')" ng-click="
                                        doSave(rec.content, 'content', 'contents', false, false, '?transTo='+lang+'&all=1'); 
                                        "> {{rec.content.id+' to '+lang}} all</button>
                                </div>
                            </div>
                            
                            <div class="grid_row row">
                                <div class="col-md-3 grid_header2"><?=__('rec_state')?></div>
                                <div class="col-md-9 notwrapped" ng-bind-html="DtSetter( 'bool2', rec.content.rec_state )"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>