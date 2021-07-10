<?php

namespace Backend\Validator;

class NodeParamsValidator extends AbstractValidator
{
    /**
     * Array of accepted languages.
     */
    private const ACCEPTED_LANGUAGES = ["italian", "english"];

    /**
     * Validates the given params.
     * @param mixed $params
     * @return bool
     */
    public static function isValid($params): bool {
        $isValid = true;

        // node_id check
        if (!isset($params['node_id'])) {
            $isValid = false;
            self::error("Missing mandatory param: node_id");
        } else if (!is_numeric($params['node_id'])) {
            $isValid = false;
            self::error("Invalid type: node_id is not a number");
        }

        // language check
        if (!isset($params['language'])) {
            $isValid = false;
            self::error("Missing mandatory params: language");
        } else if (!in_array($params['language'], self::ACCEPTED_LANGUAGES, true)) {
            $isValid = false;
            self::error("Invalid mandatory param: not accepted language");
        }

        // search_keyword check
        if (isset($params['search_keyword']) && !is_string($params['search_keyword'])) {
            self::error("Invalid type: search_keyword is not a string");
        }

        // page_num check
        if (isset($params['page_num']) && (!is_numeric($params['page_num']) || $params['page_num'] < 0)) {
            $isValid = false;
            self::error("Invalid page number requested");
        }

        // page_size check
        if (isset($params['page_size'])) {
            if (!is_numeric($params['page_size']) || ($params['page_size'] < 0 || $params['page_size'] > 1000)) {
                $isValid = false;
                self::error("Invalid page size requested");
            }
        }

        return $isValid;
    }
}