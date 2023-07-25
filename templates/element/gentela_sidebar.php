
<?php 
// "user.portfolio", "user.agency", "user.client", "user.developer", 
// "admin.content", "admin.portfolio", "admin.callcenter", "admin.supervisor", "admin.admin", "admin.root", 

$admin_menu=[
        // ["name"=>"statistics", 
        // "icon"=>"pie-chart",
        // "roles"=>["admin.root"],
        // "active"=>"/stats,/stats/props,/stats/users",
        // "sub" => [
        //         ["name"=>"general_stats", "url" => ["", "stats", ""]],
        //         ["name"=>"properties_stats",  "url" => ["", "stats", "props"]],
        //         ["name"=>"users_stats",  "url" => ["", "stats", "users"]]
        //     ]
        // ],
        // ["name"=>"categories",
        //  "icon"=>"bars",
        //  "roles"=>["admin.root"],
        // "active"=>"/categories/index/5,/categories/index/6,/categories/index/1,/categories/index/2,/categories/index/3,/categories/index/4",
        //  "sub" => [
        //         ["name"=>"project_types", "url" => ["Categories", "index", 5]],
        //         ["name"=>"property_types",  "url" => ["Categories", "index", 6]],
        //         ["name"=>"project_features", "url" => ["Categories", "index", 1]],
        //         ["name"=>"property_features",  "url" => ["Categories", "index", 2]],
        //         ["name"=>"property_specs",  "url" => ["Categories", "index", 3]],
        //         ["name"=>"project_specs", "url" => ["Categories", "index", 4]],
        //     ]
        // ],
        ["name"=>"categories",
        "icon"=>"list",
        "roles"=>["admin.root", "admin.portfolio", "admin.callcenter", "admin.supervisor", "admin.admin", "admin.content"],
        "active"=>"/categories/index,/categories/save,/categories/view",
        "sub" => [
               ["name"=>"all", "url" => ["Categories", "index", ""]],
           ]
       ],
       ["name"=>"contents",
        "icon"=>"file-text-o",
        "roles"=>["admin.root", "admin.portfolio", "admin.callcenter", "admin.supervisor", "admin.admin", "admin.content"],
        "active"=>"/contents/index,/contents/save,/contents/view",
        "sub" => [
               ["name"=>"all", "url" => ["Contents", "index", ""]],
           ]
       ],
       ["name"=>"users",
        "icon"=>"users",
        "roles"=>["admin.root", "admin.portfolio", "admin.callcenter", "admin.supervisor", "admin.admin", "admin.content"],
        "active"=>"/users/index,/users/save,/users/view",
        "sub" => [
               ["name"=>"all", "url" => ["Users", "index", ""]],
           ]
       ],
       ["name"=>"configs",
        "icon"=>"cogs",
        "roles"=>["admin.root", "admin.portfolio", "admin.callcenter", "admin.supervisor", "admin.admin", "admin.content"],
        "active"=>"/configs/index,/configs/save,/configs/view",
        "sub" => [
               ["name"=>"all", "url" => ["Configs", "index", ""]],
           ]
       ],
       ["name"=>"logs",
        "icon"=>"user-secret",
        "roles"=>["admin.root", "admin.portfolio", "admin.callcenter", "admin.supervisor", "admin.admin", "admin.content"],
        "active"=>"/logs/index,/logs/save,/logs/view",
        "sub" => [
               ["name"=>"all", "url" => ["Logs", "index", ""]],
           ]
       ],
    ];
    
    $urlparse = explode("/",str_replace('/'.$currlang, '', str_replace($app_folder, '', $_SERVER['REQUEST_URI'])));
    $urlparse[2] = empty($urlparse[2]) ? '' : $urlparse[2];
    $urlparse[3] = empty($urlparse[3]) ? '' : $urlparse[3];
    $urlparse[4] = empty($urlparse[4]) ? '' : '/'.$urlparse[4];
    // debug($urlparse);
?>

<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    
    <div class="menu_section">
        <ul class="nav side-menu">
            <?php 
                foreach($admin_menu as $itm){
                    if(!in_array($authUser["user_role"], $itm["roles"])){continue;}
                    $isActive = '';
                    if(strpos($itm["active"], '/'.$urlparse[2].'/'.$urlparse[3] ) !== false){
                        $isActive = 'active';
                    }
                    if(count($itm['sub']) == 1){
                ?>
                <li class="<?=$isActive?>">
                    <?=$this->Html->link(
                        '<i class="fa fa-'.$itm['icon'].'"></i> '.__($itm['name']), 
                        ['lang'=>$currlang, 'controller'=>$itm['sub'][0]["url"][0], 'action'=>$itm['sub'][0]["url"][1], $itm['sub'][0]["url"][2]],
                        ["escape"=>false]
                        )?>
                </li>

                <?php }else{ ?>

                <li class="<?=$isActive?>"><a><i class="fa fa-<?=$itm['icon']?>"></i> <?=__($itm['name'])?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu" style="<?=!empty($isActive) ? 'display: block' : ''?>;">
                    <?php foreach($itm['sub'] as $subitm){ ?>
                        <li><?=$this->Html->link(__($subitm['name']), ['lang'=>$currlang, 'controller'=>$subitm["url"][0], 'action'=>$subitm["url"][1], $subitm["url"][2]])?></li>
                    <?php }?>
                    </ul>
                </li>

                <?php } ?>

            <?php } ?>
        </ul>
    </div>
</div>
<!-- /sidebar menu -->