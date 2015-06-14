<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;

/**
 * Description of CategoryController
 *
 * @author jameskmb
 */
class CategoryController extends Controller {

    const CATEGORIES_PER_PAGE = 10;
    const CONTACTS_PER_PAGE = 20;

    public function getIndex() {
        $categories = Category::orderBy('title')
                ->paginate(self::CATEGORIES_PER_PAGE)
                ->setPath(\URL::current());
        return view('categories.index', compact('categories'));
    }

    public function getNew($id = null) {
        $category = $this->getCategory($id);
        return view('categories.new', compact('category'));
    }

    public function postNew($id = null) {
        $category = $this->getCategory($id);
        $rules = array(
            'title' => 'required|between:2,60',
            'description' => 'required|max:200'
        );
        $data = \Input::all();
        $validator = \Validator::make($data, $rules);
        $this->map($data, $category);
        if ($validator->fails()) {
            \Session::flash('error', 'Please Correct the higlighted errors first');
            return view('categories.new', compact('category'))->withErrors($validator->messages());
        }

        $duplicate = Category::where('title', '=', $category->title)->first();
        if (!is_null($duplicate) && $duplicate->id != $category->id) {
            \Session::flash('error', 'There Already exists a category with that title');
            return view('categories.new', compact('category'));
        }


        $category->save();
        \Session::flash('success', 'Category Successfuly Saved');
        return \Redirect::action('CategoryController@getDetails', [$category->getUrlTitle()]);
    }

    /**
     * 
     * @param string $title url encoded title
     */
    public function getDetails($title) {
        $category = $this->getCategory($title);
        $this->show404Unless($category->id);
        $contacts = $category->getContacts();
        return view('categories.details', compact('category', 'contacts'));
    }

    public function getAddContacts($id) {
        $cat = $this->getCategory($id);
        $this->show404Unless($cat->id);

        //exclude existing contacts from search
        $existing = $cat->contacts()->lists('id');
        if (count($existing)) {
            $contacts = Contact::whereNotIn('id', $existing)
                    ->orderBy('first_name')
                    ->orderBy('last_name')
                    ->paginate(self::CONTACTS_PER_PAGE)
                    ->setPath(\URL::current());
        } else {
            $contacts = Contact::paginate(self::CONTACTS_PER_PAGE)
                    ->setPath(\URL::current());
        }

        return view('categories.add-contacts', compact('cat', 'contacts'));
    }

    public function postAddContacts($id) {
        $cat = $this->getCategory($id);
        $this->show404Unless($cat->id);

        if (\Input::has('contacts') && count(\Input::get('contacts'))) {
            $contacts = \Input::get('contacts');
        } else {
            \Session::flash('error', 'Please Select The Contacts to add');
            return redirect(\URL::full());
        }


        //prevent duplicates
        $existing = $cat->contacts()->lists('id');
        foreach ($existing as $e) {
            $index = array_search($e, $contacts);
            if ($index !== false) {
                array_splice($contacts, $index, 1);
            }
        }

        if (!count($contacts)) {
            \Session::flash('error', 'Please Select The Contacts to add');
            return redirect(\URL::full());
        }

        $data = [];
        foreach ($contacts as $val) {
            if (is_numeric($val)) {
                $data[] = array(
                    'contact_id' => $val,
                    'category_id' => $cat->id
                );
            }
        }

        \DB::table('category_contact')->insert($data);
        \Session::flash('success', 'Contacts Successfuly Added To Category');
        return redirect(\URL::full());
    }
    
    public function getRemoveContact($cat, $contact) {
        $category = $this->getCategory($cat);
        $this->show404Unless($category->id);
        \DB::table('category_contact')
                ->where('category_id', '=', $cat)
                ->where('contact_id', '=', $contact)
                ->delete();
        \Session::flash('success', 'Contact Successfuly Removed from Category');
        return \Redirect::action('CategoryController@getDetails', $category->id);
    }

    private function getCategory($id) {
        if (is_null($id)) {
            return new Category();
        }

        //get by id
        if (is_numeric($id)) {
            $cat = Category::find($id);
        } else { //get by title
            $cat = Category::where('title', '=', urldecode($id))->first();
        }

        $this->show404Unless($cat);
        return $cat;
    }

    public function getDelete($id) {
        $cat = $this->getCategory($id);
        $this->show404Unless($cat->id);
        \DB::table('category_contact')
                ->where('category_id', '=', $cat->id)
                ->delete();
        $cat->delete();

        \Session::flash('success', $cat->title . ' Successfuly Deleted');
        return \Redirect::action('CategoryController@getIndex');
    }

    private function map(array $data, Category $cat) {
        if (array_key_exists('title', $data)) {
            $cat->title = str_replace('+', ' ', $data['title']);
        }

        if (array_key_exists('description', $data)) {
            $cat->description = $data['description'];
        }
    }

}
