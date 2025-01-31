<?php
    function cfoSanitizeMultipleChoice($input) {
        return $input ? filter_var_array($input, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : [];
    }
?>