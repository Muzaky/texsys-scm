@extends('layouts.master')
@section('title', 'Login')
@section('content')

<div x-data="{ showPassword: false }">
    <body class="flex items-center justify-center min-h-screen font-[Montserrat] bg-gradient-to-br from-indigo-500 via-purple-500 to-blue-500">
        <div class="bg-white/90 backdrop-blur-sm p-8 sm:p-10 rounded-2xl shadow-2xl w-[1080px] max-w-md">
            
            {{-- Header --}}
            <div class="text-center mb-8">
                <img src="{{ asset('img/Logo.png') }}" alt="Logo" class="w-48 h-auto mx-auto mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Welcome Back!</h2>
                <p class="text-gray-500 text-sm">Please enter your details to sign in.</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                
                {{-- Email Input --}}
                <div>
                    <label for="email" class="block text-gray-700 font-medium mb-2 text-sm">Email Address</label>
                    <input type="email" id="email" name="email" class="bg-gray-50 border-2 border-gray-200 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500 transition-all duration-300 @error('email') border-red-500 @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Password Input --}}
                <div>
                    <label for="password" class="block text-gray-700 font-medium mb-2 text-sm">Password</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" id="password" name="password" class="bg-gray-50 border-2 border-gray-200 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500 transition-all duration-300 @error('password') border-red-500 @enderror" required autocomplete="current-password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                            <button type="button" @click="showPassword = !showPassword" class="text-gray-500 hover:text-purple-600 focus:outline-none">
                                <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Opsi Tambahan --}}
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-gray-600">Remember me</label>
                    </div>
                    
                </div>

                {{-- Tombol Aksi --}}
                <div class="space-y-4">
                    <button type="submit" class="w-full text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition-all duration-300">
                        Sign In
                    </button>
                   
                </div>
                
                {{-- Link Sign Up --}}
             

            </form>
        </div>
        <div class="text-center mt-4 font text-white">
            <p>Under copyrighted, © 2025 Trijaya Chain</p>
        </div>
    </body>
</div>
@endsection