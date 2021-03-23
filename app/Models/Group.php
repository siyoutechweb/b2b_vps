<?php namespace App\Models;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Group extends Model {

    protected $fillable = ['name'];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    public function user()
    {
        return  $this->belongsToMany(User::class, 'group_user', 'group_id', 'user_id');
    }
    public function categorie()
    {
        return  $this->belongsToMany(Category::class, 'group_category', 'group_id', 'category_id');
    }
}
