<?php
namespace App\Models;

/**
 * Contact Category
 *
 * @author jameskmb
 */
class Category extends \Eloquent{
    const EXCERPT_SIZE = 50;
    const CONTACTS_PER_PAGE = 20;
    
    private $contacts;
    
    public function getExcerpt() {
        if(strlen($this->description) > 50) {
            return substr($this->description, 0, self::EXCERPT_SIZE). '...';
        }
        return $this->description;
    }
    
    public function contacts() {
        return $this->belongsToMany('App\Models\Contact', 'category_contact', 'category_id', 'contact_id')
                ->orderBy('first_name')
                ->orderBy('last_name');
    }
    
    public function getContacts() {
        if(is_null($this->contacts)) {
            $this->contacts = $this->contacts()
                    ->paginate(self::CONTACTS_PER_PAGE)
                    ->setPath(\URL::current());
        }
        return $this->contacts;
    }
    
    public function getUrlTitle() {
        return urlencode(strtolower($this->title));
    }
    
}
