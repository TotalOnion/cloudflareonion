<table id="global-cfo_CPTtags">
    <thead><tr class="global-cfo_tags-head">
        <th>Post Type</th>
        <th>Tag to Clear</th>
    </tr></thead>
    <?php
        if ($CPTs) {
            foreach ($CPTs as $CPT) {
                if (is_array($currentValue) && in_array($CPT, $currentValue)) {
                    $currentTag = $currentValue[$CPT];
                } else {
                    $currentTag = '';
                }?>
                <tr class="global-cfo_tags-row">
                    <td><?php echo $CPT; ?></td>
                    <td contenteditable><?php echo $currentTag; ?></td>
                </tr>
            <?php }
        } ?>
<input hidden type="text" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo esc_attr($currentValue); ?>" />
</table>


