<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Private Journal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght=300..700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Fredoka', sans-serif; }

        /* Button Wiggle */
        @keyframes cute-wiggle {
            0%, 100% { transform: translateY(-8px) rotate(-3deg); }
            50% { transform: translateY(-8px) rotate(3deg); }
        }
        .hover-wiggle:hover {
            animation: cute-wiggle 0.3s ease-in-out infinite;
        }

        /* Floating Background Elements */
        @keyframes gentle-float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }
        @keyframes twinkle {
            0%, 100% { opacity: 0.2; transform: scale(0.8); }
            50% { opacity: 1; transform: scale(1.2); }
        }
        .floating-item { animation: gentle-float 6s ease-in-out infinite; }
        .twinkle-item { animation: twinkle 4s ease-in-out infinite; }
    </style>
</head>
<body class="bg-gradient-to-tr from-rose-100 via-purple-50 to-amber-50 h-screen flex flex-col items-center justify-center relative overflow-hidden select-none">

    <div class="absolute left-[8%] top-[15%] floating-item text-4xl">🧸</div>
    <div class="absolute left-[15%] top-[45%] twinkle-item text-2xl">✨</div>
    <div class="absolute left-[5%] bottom-[25%] floating-item text-4xl">🌸</div>
    <div class="absolute left-[20%] bottom-[10%] twinkle-item text-3xl">🌟</div>
    <div class="absolute left-[25%] top-[20%] floating-item text-3xl">☁️</div>
    <div class="absolute right-[8%] top-[20%] twinkle-item text-3xl">⭐️</div>
    <div class="absolute right-[22%] top-[12%] floating-item text-4xl">🎀</div>
    <div class="absolute right-[14%] top-[50%] floating-item text-3xl">🐈</div>
    <div class="absolute right-[6%] bottom-[30%] twinkle-item text-4xl">✨</div>
    <div class="absolute right-[18%] bottom-[8%] floating-item text-4xl">💖</div>

    <div class="text-center mb-12 z-10">
        <h1 class="text-5xl font-bold text-slate-800 mb-3 tracking-wide drop-shadow-sm">Our Journal ✨</h1>
        <p class="text-slate-500 text-lg">Who is entering the journal today?</p>
    </div>

    <div class="flex space-x-10 z-10">
        <a href="/login-kerb" class="hover-wiggle w-48 h-48 bg-zinc-900 text-white font-medium rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-300 flex flex-col items-center justify-center space-y-4 border-4 border-zinc-800">
            <div class="bg-zinc-800 p-4 rounded-full text-4xl shadow-inner">🐈‍⬛</div>
            <span class="text-xl tracking-wider font-semibold">Kerb</span>
        </a>

        <a href="/login-yannie" class="hover-wiggle w-48 h-48 bg-rose-400 text-white font-medium rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-300 flex flex-col items-center justify-center space-y-4 border-4 border-rose-300">
            <div class="bg-rose-300/50 p-4 rounded-full text-4xl shadow-inner flex items-center justify-center">
                🌷
            </div>
            <span class="text-xl tracking-wider font-semibold">Yannie</span>
        </a>
    </div>

</body>
</html>