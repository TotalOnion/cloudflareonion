<textarea
    name="<?php echo $name; ?>"
    id="<?php echo $id; ?>"
    class="<?php echo $cssClass; ?>"
    rows="10"
    ><?php echo esc_textarea($currentValue); ?>
</textarea>
<p class="description">
    <?php echo $description ?? '' ?>
</p>