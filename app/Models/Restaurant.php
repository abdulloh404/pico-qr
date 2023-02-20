<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;
    protected $fillable=['name','email','phone_number','template','location','timing','user_id','description','profile_image','cover_image','slug','status','currency_code','currency_symbol','order_status','cash_on_delivery','takeaway','table_booking','direction','delivery_fee','on_multi_restaurant'];

    public function user(){
        return $this->belongsTo(User::class)->withDefault();
    }
    public function items(){
        return $this->hasMany(Item::class);
    }
    public function tables(){
        return $this->hasMany(Table::class);
    }
    public function custom_menus(){
        return $this->hasMany(CustomMenu::class);
    }

    public function setDescriptionAttribute($value){
        $this->attributes['description']=clean($value);
    }
    public function restaurant(){
        return $this->belongsTo(Restaurant::class,'restaurant_id','id');
    }
}
