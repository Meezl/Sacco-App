<?php
namespace App\Models;

/**
 * Description of Image
 *
 * @author James
 */
class Image extends \Eloquent{
    
    protected $guarded = array('id');
    
    const CROP_WIDTH = 100;
    const CROP_HEIGHT = 75;
    
    
    /**
     * Get the filename of the croped verion of this image
     * @return type
     */
    public function getCropped(){
        return is_null($this->filename)? null:'crop_'.$this->filename;
    }
    
}
