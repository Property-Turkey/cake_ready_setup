

<?php 
$placeholder = empty($placeholder) ? __('add_tag') : $placeholder;
?>
<?php if(!empty($resource)){?>

        <tags-input ng-model="<?=$ng?>" 
                placeholder="Add a country" 
                replace-spaces-with-dashes="false"
                add-from-autocomplete-only="true"
                on-tag-adding="tagsHandler('add', $tag)"
                on-tag-removing="tagsHandler('delete', $tag)"
                display-property="countryName"
                >
                <auto-complete source="loadCountries($query)"
                     load-on-focus="true"
                     load-on-empty="true"
                     min-length="0"
                     add-from-autocomplete-only="true"
                     display-property="countryName"
                     max-results-to-show="20"></auto-complete>
        </tags-input>

<?php }else{?>
        
        <tags-input ng-model="<?=$ng?>" use-strings="true" 
                replace-spaces-with-dashes="false"
                placeholder="<?=$placeholder?>"
        > </tags-input>

<?php }?>