<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unlock Your Journal 🗝️</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Fredoka', sans-serif; }
        
        /* Smooth Page-Wide Fade In */
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.98); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body class="bg-gradient-to-tr from-rose-100 via-purple-50 to-amber-50 h-screen flex flex-col items-center justify-center relative overflow-hidden select-none animate-fade-in">
    @php
        // Detect if it's the girlfriend based on the current request OR the previous page URL
        $isGf = request('user') === 'gf' || str_contains(url()->previous(), 'user=gf');
    @endphp

    <div class="w-full max-w-sm bg-white/70 backdrop-blur-md p-8 rounded-3xl shadow-xl border-2 border-white/50 text-center z-10">
        
        <div class="w-20 h-20 mx-auto mb-4 flex items-center justify-center rounded-full text-4xl shadow-inner 
            {{ $isGf ? 'bg-rose-300/50' : 'bg-zinc-800 text-white' }}">
            {{ $isGf ? '🌷' : '🐈‍⬛' }}
        </div>

        <h2 class="text-2xl font-bold text-slate-800 mb-2">
            Welcome Back, {{ $isGf ? 'My GF' : 'Me' }}!
        </h2>
        <p class="text-slate-400 text-sm mb-6">Enter your private key to open the journal</p>

        @error('password')
            <div class="mb-4 p-3 bg-rose-100 text-rose-600 rounded-2xl text-sm font-medium animate-bounce">
                {{ $message }}
            </div>
        @enderror

        <form action="/login" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="user_type" value="{{ $isGf ? 'gf' : 'me' }}">
            
            <input type="password" name="password" placeholder="••••" class="w-full px-4 py-3 bg-white/80 border-2 border-slate-200 rounded-2xl text-center text-xl tracking-widest focus:outline-none focus:border-purple-300 transition-colors">
            
            <button type="submit" class="w-full py-3 rounded-2xl font-semibold shadow-md text-white transition-all duration-200 hover:-translate-y-0.5
                {{ $isGf ? 'bg-rose-400 hover:bg-rose-500' : 'bg-zinc-900 hover:bg-black' }}">
                Open Journal 📖
            </button>
        </form>

        <a href="/" class="inline-block mt-6 text-sm text-slate-400 hover:text-slate-600 transition-colors">← Go Back</a>
    </div>

</body>
</html>