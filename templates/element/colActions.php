 
<?php
    $ctrl = strtolower($this->request->getParam('controller'));
    $from = $this->request->getQuery('from');
    $to = $this->request->getQuery('to');
    $method = empty($method) ? (isset($search) ? 'like' : 'filter') : $method;
    if($ctrl != 'results'){
?>

<div style="min-width: 80px;">



    <?php if(isset($col)){ // Sort by column?>
    <span>
        <a href class="btnIcon" ng-click="
            sort['<?=$col?>'] = sort['<?=$col?>'] == 'ASC' ? 'DESC' : 'ASC';
            rec.search.col = '<?=$col?>';
            rec.search.direction = sort['<?=$col?>'];
            doGetDelay('/admin/<?=$url?>?from=<?=@$from?>&to=<?=@$to?>&direction='+sort['<?=$col?>']+'&col=<?=$col?>&list=1', 'list', '<?=$ctrl?>');" > 
                <i class="fa fa-{{sort['<?=$col?>']=='ASC' ? 'sort-amount-asc' : 'sort-amount-desc'}}"></i> 
        </a>
    </span>
    <?php }?>



    <?php if(isset($search)){ // Search keyword?>
    <span  click-outside="
                    isSearch=[];
                        ">
        <a href class="btnIcon" ng-click="
                isSearch['<?=$col?>'] == 'open' ? isSearch = [] :  isSearch = [] ; 
                isSearch['<?=$col?>'] = 'open';
            " > <i class="fa fa-search"></i> </a>

        <div class="input-on-fly {{isSearch['<?=$col?>'] == 'open' ? '' : 'hideIt'}}">
            <?=$this->Form->control($col, [
                'empty'=>true,
                'label'=>false,
                'type'=>'text',
                'name'=>false,
                'ng-model'=>'filter.kword',
                'ng-change' => "
                    doGetDelay('/admin/".$url."?from=".@$from."&to=".@$to."&k='+filter.kword+'&col=".$search."&method=".$method."&list=1' , 'list', '".$ctrl."');
                "
            ])?>
        </div>
    </span>
    <?php }?>




    <?php if(isset($filter)){ // Filter value?>
    <span  click-outside="
                    isSearch=[];
                        ">
        <a href class="btnIcon" ng-click="
                isSearch['<?=$col?>'] == 'open' ? isSearch = [] :  isSearch = [] ; 
                isSearch['<?=$col?>'] = 'open';
            " > <i class="fa fa-filter"></i> </a>

        <div class="input-on-fly {{isSearch['<?=$col?>'] == 'open' ? '' : 'hideIt'}}">
            <?=$this->Form->control($col, [
                'label'=>false,
                'empty'=>true,
                'type'=>'select',
                'ng-model'=>'filter.kword',
                'options'=>$filter,
                'ng-change' => "
                    rec.search.".$col."=filter.kword; 
                    doGetDelay('/admin/".$url."?from=".$from."&to=".$to."&k='+filter.kword+'&col=".$col."&method=".$method."&list=1' , 'list', '".$ctrl."');
                "
            ])?>
        </div>
    </span>
    <?php }?>



 </div>

 <?php }?>