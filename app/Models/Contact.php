<?php

namespace App\Models;

/**
 * Description of Contact
 *
 * @author jameskmb
 */
class Contact extends \Eloquent {

    public function getFullName() {
        return $this->first_name . ' ' . $this->last_name;
    }

}
