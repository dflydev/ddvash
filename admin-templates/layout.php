<div class="wrap">

<h2>ddVash Theme Layout</h2>

<form method="post" action="options.php">

    <?php settings_fields( 'ddvash-layout-group' ); ?>
    <h3>Global</h3>
    <table class="form-table">
    
        <tr valign="top">
        <th scope="row">Width</th>
        <td><input type="text" name="ddvash_layout_global_width" value="<?php echo get_option('ddvash_layout_global_width'); ?>" /></td>
        </tr>
        
    </table>

    <h3>Header Image</h3>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Show?</th>
            <td>
                <?php
                    if(get_option('ddvash_layout_header_image_show')){
                        $checked = "checked=\"checked\"";
                    }else{
                        $checked = "";
                    }
                ?>
                <input type="checkbox" name="ddvash_layout_header_image_show" id="ddvash_layout_header_image_show" value="true" <?php echo $checked; ?> />
                <label for="ddvash_layout_header_image_show"><?php echo __('Show a header image?','ddvash'); ?></label>
            </td>
        </tr>    
        <tr valign="top">
        <th scope="row">Width</th>
        <td><input type="text" name="ddvash_layout_header_image_width" value="<?php echo get_option('ddvash_layout_header_image_width'); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Height</th>
        <td><input type="text" name="ddvash_layout_header_image_height" value="<?php echo get_option('ddvash_layout_header_image_height'); ?>" /></td>
        </tr>
        
    </table>

    <h3>Sidebar</h3>
    <table class="form-table">
    
        <tr valign="top">
        <th scope="row">Location</th>
        <td>
        <select name="ddvash_layout_sidebar_location">
            <?php foreach ( array('right', 'left', 'none') as $location ) { ?>
            <option value="<?php echo $location ?>" <?php if ( $location == get_option('ddvash_layout_sidebar_location') ) { echo ' SELECTED="SELECTED"'; } ?>><?php echo $location; ?></option>
            <?php } ?>
        </select>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Width</th>
        <td><input type="text" name="ddvash_layout_sidebar_width" value="<?php echo get_option('ddvash_layout_sidebar_width'); ?>" /></td>
        </tr>
        
    </table>
        
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>

</div>