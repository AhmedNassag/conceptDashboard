<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use  HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'role_name' => 'array',
        'status' => 'integer'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {
        return [];
    }

    // set hash password

    public function setPasswordAttribute($value){
        $this->attributes['password'] = bcrypt($value);
    }

    //============== appends paths ===========

    protected $appends = [
        'image_path',
        'sum_account'
    ];

    //append img path

    public function getImagePathAttribute(): string
    {
        $file_name = '';
        if($this->media){
            $file_name = $this->media->file_name;
        }
        return asset('upload/user/'.$file_name);
    }

    public function getSumAccountAttribute(): float
    {
        if ($this->whereAuthId(2)->whereJsonContains('role_name','client')){
            return $this->clientAccounts->sum('amount');
        }
        return 0;
    }

    // start Relation

    public function media()
    {
        return $this->morphOne(Media::class,'mediable');
    }
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function representative()
    {
        return $this->hasOne(Representative::class);
    }

    public function banks()
    {
        return $this->hasOne(Bank::class,'user_id');
    }

    public function examinationRecords (){
        return $this->hasMany(ExaminationRecord::class,'user_id');
    }

    public function purchaseReturns (){
        return $this->hasMany(PurchaseReturn::class,'user_id');
    }

    public function clientPurchaseReturns (){
        return $this->hasMany(PurchaseReturn::class,'client_id');
    }

    public function pricingHistories(){
        return $this->hasMany(PricingHistory::class);
    }

    public function complement(){
        return $this->hasOne(Complement::class);
    }

    public function client(){
        return $this->hasOne(Client::class);
    }

    public function userCompany(){
        return $this->hasOne(UserCompany::class);
    }

    public function merchant(){
        return $this->hasOne(Merchant::class);
    }

    public function suggestionUser(){
        return $this->hasMany(SuggestionUser::class);
    }

    public function clientAccounts(){
        return $this->hasMany(ClientAccount::class,'user_id','id');
    }

    public function purchases(){

        return $this->hasMany(Purchase::class);
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'App.Models.User.'.$this->id;
    }

    public function addSupplierExpense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupplierExpense::class,'user_id');
    }

    public function addClientExpense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientExpense::class,'user_id');
    }

    public function clientExpense(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientExpense::class,'client_id');
    }

    public function supplierIncome(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupplierIncome::class);
    }

    public function addClientIncome(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientExpense::class,'user_id');
    }

    public function clientIncome(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientExpense::class,'client_id');
    }

    public function clientOrders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class,'user_id');
    }

    public function representativeOrders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class,'representative_id');
    }

    public function code()
    {
        return $this->hasOne(Share::class,'user_id');
    }

}
