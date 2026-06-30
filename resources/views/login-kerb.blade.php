<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unlock Kerb's Journal 🔐</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Fredoka', sans-serif; }</style>
</head>
<body class="bg-gradient-to-tr from-slate-200 via-slate-100 to-zinc-200 min-h-screen flex flex-col items-center justify-center p-4">

    <div class="bg-white rounded-[2.5rem] shadow-xl p-8 max-w-sm w-full text-center border border-slate-100">
        <!-- Profile Avatar -->
        <div class="w-20 h-20 bg-slate-800 text-4xl rounded-full flex items-center justify-center mx-auto shadow-inner">
            🐈‍⬛
        </div>

        <h1 class="text-2xl font-bold text-slate-800 mt-4">Welcome Back, Kerb!</h1>
        <p class="text-sm text-slate-400 mt-1">Enter the key, ya know it!</p>

        @if(session('error'))
            <div class="bg-rose-50 text-rose-600 text-xs font-semibold rounded-2xl p-3 mt-4 flex items-center justify-center space-x-1">
                <span>Oops! That is not the correct secret key. 🔑</span>
            </div>
        @endif

        <!-- Form sends name hiddenly so AuthController knows who is logging in -->
        <form action="/login" method="POST" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="name" value="Kerb">
            
            <input type="password" name="password" required autofocus placeholder="••••" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-center text-xl tracking-widest focus:outline-none focus:border-slate-400 transition-colors">
            
            <button type="submit" class="w-full py-3.5 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-2xl shadow-md text-sm transition-all">
                Open Journal 📖
            </button>
        </form>

        <a href="/" class="text-xs text-slate-400 hover:text-slate-600 inline-block mt-6 transition-colors">← Go Back</a>
    </div>

</body>
</html>