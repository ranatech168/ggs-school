<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;use Illuminate\Support\Facades\Auth;
class AuthController extends Controller {
 public function form(){if(Auth::check()){return redirect()->route('admin.dashboard');}return view('auth.login');}
 public function login(Request $r){$data=$r->validate(['email'=>'required|email','password'=>'required|string']);if(Auth::attempt($data,$r->boolean('remember'))){$r->session()->regenerate();return redirect()->intended(route('admin.dashboard'));}return back()->withErrors(['email'=>'Invalid email or password.'])->onlyInput('email');}
 public function logout(Request $r){Auth::logout();$r->session()->invalidate();$r->session()->regenerateToken();return redirect()->route('home');}
}
