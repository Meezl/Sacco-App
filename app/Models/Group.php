<?php
namespace App\Models;

/**
 * Catgories a member can belong to
 *
 * @author jameskmb
 */
class Group extends \Eloquent{
    
    /**
     * Retrieve all groups ordered by title
     */
    public static function ordered() {
        return self::orderBy('title')->get();
    }
}
