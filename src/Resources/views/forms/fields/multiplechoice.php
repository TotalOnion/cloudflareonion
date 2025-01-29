<select id="<?php echo $name; ?>" name="<?php echo $name; ?>[]" multiple class="snapSelect">
    <?php
    foreach ($choices as $choice) { ?>
        <option value="<?php echo $choice ?>" 
        <?php selected(in_array($choice, $currentValue)) ?> class="<?php echo $cssClass; ?>">
        <?php echo $choice ?></option>
    <?php } ?>
</select>