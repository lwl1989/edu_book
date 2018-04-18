<?php


namespace App\Services;


use App\Models\Admin;
use App\Services\Users\UserReceiveTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminService extends ServiceBasic
{
    use UserReceiveTrait;
    protected $model = Admin::class;

    /**
     * @param array $passwords
     * @return bool
     */
    public static function changePassword(array $passwords) : bool
    {
        $user = Auth::user();
        if(Hash::check($passwords['oldPassword'], $user->password)) {
            self::getModelInstance()::where('id',$user->id)->update(['password'=>bcrypt($passwords['newPassword'])]);
            return true;
        }
        return false;
    }
}