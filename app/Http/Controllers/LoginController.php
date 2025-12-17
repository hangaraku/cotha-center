<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $email = $request->email;
        $password = $request->password;

        // Check if input is email or username
        $isEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
        
        if (!$isEmail) {
            // Input is username, find user by username (part before @ in email)
            $users = User::where('email', 'LIKE', $email . '@%')->get();
            
            if ($users->count() > 1) {
                // Multiple users found with same username
                return back()->withErrors([
                    'email' => 'Tidak dapat login menggunakan username, coba dengan email lengkap.',
                ])->onlyInput('email');
            } elseif ($users->count() === 1) {
                // Single user found, use their email
                $email = $users->first()->email;
            } else {
                // No user found
                return back()->withErrors([
                    'email' => 'Your provided credentials do not match in our records.',
                ])->onlyInput('email');
            }
        }

        // Attempt login with email
        if(Auth::attempt(['email' => $email, 'password' => $password]))
        {
            $request->session()->regenerate();
            
            // Check if user has admin roles (either in admin_roles table or Spatie roles)
            $hasAdminRoles = !Auth::user()->admins->isEmpty();
            $hasSpatieAdminRole = Auth::user()->hasRole(['super_admin', 'Teacher', 'Supervisor']);
            
            if($hasAdminRoles || $hasSpatieAdminRole){
                return redirect(url('/admin'));
            }else{
                return redirect()->route('home')
                ->withSuccess('You have successfully logged in!');
            }
        }

        return back()->withErrors([
            'email' => 'Your provided credentials do not match in our records.',
        ])->onlyInput('email');
    } 

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')
            ->withSuccess('You have logged out successfully!');;
    }


    public function register(Request $request)
    {
        $rules = [
            'fullname' => 'required|string|max:255',
            'password' => 'required|string|min:3', // Adjust the min length as needed
            'email' => 'required|email|unique:users,email',
            'center' => 'required|integer', // Assuming center_id is an integer field
        ];
    
        // Create custom validation messages if needed
        $messages = [
            'email.unique' => 'The email address is already in use.',
        ];
    
        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

       $new = User::create([
        "name" => $request->fullname,
        "password"=> $request->password,
        "email"=>$request->email,
        "center_id"=>$request->center,
        "gender"=>$request->gender
       ]);
       if($new){
        Student::create([
            "user_id" => $new->id,
            "school" => $request->school,
            "city" => $request->city,
            "birthdate" => $request->birthdate,
            "phone"=>$request->phone
            
       ]);
       return redirect()->route('login');
       }
       return redirect()->route('register');

    }
}
