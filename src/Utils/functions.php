<?php
    function encodeNonLatinCharacters($string) {
        // Only encode weird characters
        if(preg_match('/[^\x20-\x7e]/', $string)) {
            $segments = explode('/', $string);
            
            // Encode each segment but skip empty ones (which come from leading/trailing slashes)
            $encodedSegments = array_map(function($segment) {
                return $segment === '' ? '' : strtolower(rawurlencode($segment));
            }, $segments);
            
            // Recombine the segments using slashes
            return implode('/', $encodedSegments);
            
        }

        return $string;
    }

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

        // Reject non ascii characters
        if (preg_match('/[^\x01-\x7F]/', $decoded)) {
            return false;
        }

        return true;
    }

    function cfoGetWPMLLanguageById($id) {
        $market = null;
        if ( in_array( 'sitepress-multilingual-cms/sitepress.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            $languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );
            foreach ($languages as $key => $language) {
                if ($language['id'] == $id) {
                    $market = $language;
                }
            }
        }
        return $market;
    }
