<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected $rules = [
        'name' => 'required|min:2|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
    ];

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'is_admin' => false,
        ]);

        Auth::login($user);

        return redirect()->route('scanner')->with('message', 'Welcome! Account created successfully. Scan your first resume now.');
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.app', ['title' => 'Create Account — ATS Pulse']);
    }
}
