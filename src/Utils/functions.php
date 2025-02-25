<?php
    function cfoSanitizeMultipleChoice($input) {
        return $input ? filter_var_array($input, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : [];
    }

    function isValidUrlPath($path) {
        // Remove any url encoded characters
        $decoded = urldecode($path);

        // Must start with /
        if (substr($decoded, 0, 1) !== '/') {
            return false;
        }

        // Check for invalid URL path characters
        if (preg_match('/[<>"\{\}\|\\\^\[\]`]/', $decoded)) {
            return false;
        }

        // Check for control characters
        if (preg_match('/[\x00-\x1F\x7F]/', $decoded)) {
            return false;
        }

        return true;
    }

    function cfoGetWPMLLanguageById($id) {
        $market = null;
        $languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );
        foreach ($languages as $key => $language) {
            if ($language['id'] == $id) {
                $market = $language;
            }
        }
        return $market;
    }
