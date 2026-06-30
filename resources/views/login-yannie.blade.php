<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unlock Yannie's Journal 🔐</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Fredoka', sans-serif; }</style>
</head>
<body class="bg-gradient-to-tr from-rose-100 via-purple-50 to-amber-50 min-h-screen flex flex-col items-center justify-center p-4">

    <div class="bg-white rounded-[2.5rem] shadow-xl p-8 max-w-sm w-full text-center border border-rose-100/40">
        <!-- Profile Avatar - Changed from deep red to soft cozy pink -->
        <div class="w-20 h-20 bg-rose-300 text-4xl rounded-full flex items-center justify-center mx-auto shadow-inner">
            🌷
        </div>

        <h1 class="text-2xl font-bold text-slate-800 mt-4">Welcome Back, Yannie!</h1>
        <p class="text-sm text-slate-400 mt-1">Enter the key, ya know it!</p>

        @if(session('error'))
            <div class="bg-rose-50 text-rose-500 text-xs font-semibold rounded-2xl p-3 mt-4 flex items-center justify-center space-x-1 border border-rose-100">
                <span>Oops! That is not the correct secret key. 🔑</span>
            </div>
        @endif

        <form action="/login" method="POST" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="name" value="Yannie">
            
            <input type="password" name="password" required autofocus placeholder="••••" class="w-full px-4 py-3 bg-rose-50/40 border border-rose-100 rounded-2xl text-center text-xl tracking-widest text-rose-600 placeholder-rose-200 focus:outline-none focus:border-rose-300 transition-colors">
            
            <!-- Button - Changed to soft, pretty pink -->
            <button type="submit" class="w-full py-3.5 bg-rose-400 hover:bg-rose-500 text-white font-bold rounded-2xl shadow-md text-sm tracking-wider transition-all hover:scale-[1.01]">
                Open Journal 📖
            </button>
        </form>

        <a href="/" class="text-xs text-rose-400 hover:text-rose-500 inline-block mt-6 transition-colors">← Go Back</a>
    </div>

</body>
</html>