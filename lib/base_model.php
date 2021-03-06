<?php

class BaseModel {

    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    public $validators;

    public function __construct($attributes = null) {
        // Käydään assosiaatiolistan avaimet läpi
        foreach ($attributes as $attribute => $value) {
            // Jos avaimen niminen attribuutti on olemassa...
            if (property_exists($this, $attribute)) {
                // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
                $this->{$attribute} = $value;
            }
        }
    }

    public function string_notempty_validator($string, $field_name) {
        $errors = '';
        if ($string == NULL || $string == '') {
            $errors = $field_name . ' kenttä ei saa olla tyhjä';
        }
        return $errors;
    }

    public function string_length_validator($string, $length, $field_name) {
        $errors = '';
        if (strlen($string) < $length) {
            $errors = $field_name . ' kentän syötteen pituus väintään ' . $length . " merkkiä.";
        }
        return $errors;
    }

    public function year_validator($string) {
        $errors = '';
        if (!preg_match("/^[12][09][0-9]{2}$/", $string)) {
            $errors = 'Tarkista vuosiluku';
        }
        return $errors;
    }

    public function date_validator($string) {
        $year_now = date('Y');
        $errors = '';
        if (preg_match("/^([0-9]{1,2})\\.([0-9]{1,2})\\.([0-9]{4})$/", $string)) {
            list($day, $month, $year) = explode(".", $string);
            goto mo_cheking;
        } else if (preg_match("/^[0-9]{4}\\-[0-9]{2}\\-[0-9]{2}$/", $string)) {
            list($year, $month, $day) = explode("-", $string);
        } else {
            $errors = 'Tarkista päivämäärä';
            return $errors;
        }
        mo_cheking:
        if (!checkdate($month, $day, $year) || $year < 1900 || $year > $year_now) {
            $errors = 'Tarkista päivämäärä';
        }
        return $errors;
    }

    public function time_validator($string) {
        $errors = '';
        if (!preg_match("/^(([01]?[0-9])|2[0-3])(:|\.)[0-5][0-9]$/", $string)) {
            $errors = 'Tarkista kellonaika';
        }
        return $errors;
    }

      public function errors() {
        $errors = array();

        foreach ($this->validators as $validator) {
            $validator_errors = $this->{$validator}();

            foreach ($validator_errors as $validator_error) {
                if ($validator_error != '') {
                    $errors[] = $validator_error;
                }
            }
        }
        return $errors;
    }

}
