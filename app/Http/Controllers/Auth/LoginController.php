<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Library\Constant\StaticRoute;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $username = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);


        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        $this->sendFailedLoginResponse($request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sendLoginResponse(Request $request) : JsonResponse{
        //這裏可以寫個多的邏輯
        $user = $this->guard()->user();
        if(strpos($request->path(),'api/')===0) {
            unset($user->password);
            return response()->json(['token'=>Crypt::encrypt($user->getAuthIdentifier()),'user'=>$user]);
        }else{
            $request->session()->regenerate();

            $this->clearLoginAttempts($request);

            return response()->json(['code'=>0])
                ->withCookie(Cookie::forever('type', $request->input('type','student'),null,null,false,false));
//                ->withCookie(Cookie::forever('type',$request->input('type','student')));
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }


    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username() : string
    {
        if($this->username == '') {
            $type = \request()->input('type','student');
            $this->username = $type == 'student' ? 'student_num' : (
            $type == 'teacher' ? 'teacher_num' : 'account'
            );
        }
        return $this->username;
    }


    /**
     * @param Request $request
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages(array_merge($request->all(),[
            $this->username() => [trans('auth.failed')],
        ]));
    }
}
