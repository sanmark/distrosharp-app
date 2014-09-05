<?php

Validator::extend ( 'hash_match' , 'ValidationRules\CustomValidationRules@hashMatch' , 'The :attribute field doesn\'t match.' ) ;

Validator::extend ( 'at_least_one_array_element_has_value' , 'ValidationRules\CustomValidationRules@atLeastOneArrayElementHasValue' ) ;

Validator::extend ( 'at_least_one_element_of_one_array_has_value' , 'ValidationRules\CustomValidationRules@atLeastOneElementOfOneArrayHasValue' ) ;

Validator::extend ( 'greater_than_or_equal_to' , 'ValidationRules\CustomValidationRules@greaterThanOrEqualTo' ) ;

Validator::extend ( 'no_spaces_in_string' , 'ValidationRules\CustomValidationRules@noSpacesInString' ) ;
