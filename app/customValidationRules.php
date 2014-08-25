<?php

Validator::extend ( 'hash_match' , 'ValidationRules\CustomValidationRules@hashMatch' , 'The :attribute field doesn\'t match.' ) ;

Validator::extend ( 'at_least_one_array_element_has_value' , 'ValidationRules\CustomValidationRules@atLeastOneArrayElementHasValue',NULL ) ;
