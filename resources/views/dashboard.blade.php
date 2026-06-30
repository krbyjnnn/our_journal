<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ auth()->user()->name }}'s Secret Journal 📖</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Fredoka', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gradient-to-tr from-rose-100 via-purple-50 to-amber-50 min-h-screen flex flex-col items-center justify-center p-4 select-none" x-data="{ spread: 'cover', isWriting: false }">

    <div x-show="spread === 'cover'" x-transition:enter="transition ease-out duration-300" class="text-center cursor-pointer max-w-sm w-full group animate-fade-in" @click="spread = 'index'">
        <div class="w-64 h-80 mx-auto bg-gradient-to-b {{ auth()->user()->name === 'Me' ? 'from-slate-700 to-slate-900 border-slate-950' : 'from-rose-700 to-rose-900 border-rose-950' }} rounded-r-3xl shadow-2xl border-l-8 flex flex-col items-center justify-center space-y-6 transform group-hover:scale-105 group-hover:rotate-1 transition-all duration-300 relative">
            <div class="absolute top-0 bottom-0 left-2 w-[1px] bg-amber-400/30"></div>
            <div class="w-20 h-20 bg-amber-100/10 border-2 border-amber-300/40 rounded-full flex items-center justify-center text-4xl shadow-inner animate-pulse">
                {{ auth()->user()->name === 'Me' ? '🐈‍⬛' : '🌷' }}
            </div>
            <div class="text-center px-4">
                <h2 class="text-xl font-bold text-amber-100 tracking-widest drop-shadow uppercase">{{ auth()->user()->name }}'s JOURNAL</h2>
                <p class="text-[10px] text-amber-200/60 mt-1 uppercase tracking-wider">Tap Cover to Open</p>
            </div>
        </div>
    </div>

    <div x-show="spread !== 'cover'" x-transition:enter="transition ease-out duration-500 transform" class="w-full max-w-5xl flex flex-col items-center space-y-6" x-cloak>
        
        <div class="w-full bg-amber-50/40 border border-amber-900/10 rounded-[2.5rem] shadow-2xl p-4 md:p-8 relative">
            <div class="grid grid-cols-1 md:grid-cols-2 bg-[#fcf9f2] rounded-[2rem] shadow-inner border border-amber-100 min-h-[550px] divide-y md:divide-y-0 md:divide-x divide-amber-200/60 relative overflow-hidden">
                
                <template x-if="spread === 'index'">
                    <div class="p-6 md:p-10 flex flex-col justify-between bg-gradient-to-l from-transparent to-amber-50/30 h-full">
                        <div>
                            <div class="flex items-center space-x-2 text-slate-400 font-bold tracking-wide text-xs mb-6">
                                <span>💭 RANDOM THOUGHT LOG</span>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-800 mb-4">Hey, {{ auth()->user()->name }}...</h3>
                            
                            @if(auth()->user()->name === 'Me')
                                <p class="text-slate-600 text-sm leading-relaxed mb-4">Just writing down random ideas, system designs, or late-night thoughts before they fly away.</p>
                                <p class="text-slate-400 text-xs italic">"Code works, database is clean, thoughts are wandering."</p>
                            @else
                                <p class="text-slate-600 text-sm leading-relaxed mb-4">Welcome to your personal diary corner. Drop your random feelings, stories, or highlights here.</p>
                                <p class="text-slate-400 text-xs italic">"A quiet space to think, reflect, and doodle with words."</p>
                            @endif
                        </div>
                        <div class="text-xs text-slate-300 font-mono">Personal Workspace 📂</div>
                    </div>
                </template>

                <template x-if="spread === 'index'">
                    <div class="p-6 md:p-10 flex flex-col justify-between bg-gradient-to-r from-transparent to-amber-50/10 h-full">
                        <div>
                            <div class="flex justify-between items-baseline mb-6 border-b border-amber-200/60 pb-3">
                                <h3 class="text-xl font-bold text-slate-800 tracking-wide">My Chapters</h3>
                                <span class="text-xs font-bold text-slate-400 tracking-widest uppercase">Index ({{ $entries->count() }})</span>
                            </div>

                            <div class="overflow-y-auto max-h-[380px] pr-1 space-y-3">
                                @if($entries->isEmpty())
                                    <div class="py-12 text-center">
                                        <span class="text-4xl block mb-2">🍃</span>
                                        <p class="text-slate-400 text-sm">Your index page is completely clean.</p>
                                    </div>
                                @else
                                    @foreach($entries as $index => $entry)
                                        <div class="flex items-center justify-between p-3 rounded-xl border border-transparent hover:bg-white/60 hover:border-amber-200/60 transition-all cursor-pointer group">
                                            <div class="flex items-center space-x-3 truncate">
                                                <span class="text-xs font-mono text-slate-300 font-bold">Ch. 0{{ $index + 1 }}</span>
                                                <span class="text-sm text-slate-400">{{ $entry->mood ?? '¼' }}</span>
                                                <h4 class="text-sm font-semibold text-slate-700 truncate group-hover:text-purple-600 transition-colors">
                                                    {{ $entry->title }}
                                                </h4>
                                            </div>
                                            <span class="text-xs font-mono text-slate-300 ml-2">p. {{ $entry->created_at->format('m/d') }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="text-right text-xs font-mono text-slate-300">Page 01</div>
                    </div>
                </template>


                <template x-if="spread === 'pages'">
                    <div class="p-6 md:p-10 flex flex-col justify-between h-full bg-gradient-to-l from-transparent to-amber-50/30">
                        <div class="h-full flex flex-col justify-between">
                            <div class="flex-1 flex flex-col justify-center items-center text-center px-4">
                                <div x-show="!isWriting" class="space-y-4">
                                    <button @click="isWriting = true" class="group w-20 h-20 bg-gradient-to-tr from-zinc-700 to-zinc-900 text-white font-bold text-3xl flex items-center justify-center shadow-lg hover:scale-105 transition-all mx-auto border-4 border-white rounded-full">
                                        <span class="group-hover:rotate-90 transition-transform duration-300">+</span>
                                    </button>
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Add New Chapter Sheet</p>
                                </div>

                                <div x-show="isWriting" class="w-full text-left" x-cloak>
                                    <form action="/entries" method="POST" class="space-y-4">
                                        @csrf
                                        <div class="flex justify-between items-center border-b border-amber-200 pb-1">
                                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">New Log</span>
                                            <button type="button" @click="isWriting = false" class="text-xs text-rose-400">Cancel</button>
                                        </div>
                                        
                                        <input type="text" name="title" required placeholder="Chapter Title..." class="w-full px-0 py-1 bg-transparent border-b border-transparent focus:outline-none focus:border-slate-400 text-lg font-bold text-slate-800 placeholder:text-slate-300 transition-colors">
                                        
                                        <textarea name="content" required rows="6" placeholder="Start writing..." class="w-full px-0 bg-transparent resize-none focus:outline-none text-slate-600 placeholder:text-slate-300 leading-relaxed text-sm"></textarea>

                                        <div class="flex items-center space-x-3 pt-2">
                                            <label class="text-xs font-bold text-slate-400">Badge Sticker:</label>
                                            <select name="mood" class="bg-white border border-slate-200 rounded-xl px-2 py-1 text-xs text-slate-700 focus:outline-none">
                                                <option value="📓">📓 Entry</option>
                                                <option value="✨">✨ Magic</option>
                                                <option value="🐈‍⬛">🐈‍⬛ Custom Badge</option>
                                            </select>
                                        </div>

                                        <button type="submit" class="w-full py-2.5 bg-zinc-950 text-white font-semibold rounded-xl hover:bg-black text-xs uppercase tracking-wider transition-all">
                                            Save Chapter 💾
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="spread === 'pages'">
                    <div class="p-6 md:p-10 flex flex-col justify-between h-full bg-gradient-to-r from-transparent to-amber-50/10">
                        <div class="flex-1 flex flex-col justify-center items-center border border-dashed border-amber-200/60 bg-white/40 rounded-2xl p-6 text-center">
                            <span class="text-2xl mb-2 opacity-40">📝</span>
                            <h4 class="text-xs font-semibold text-slate-400">Binding margin</h4>
                        </div>
                        <div class="text-right text-xs font-mono text-slate-300 pt-4">
                            Page 02
                        </div>
                    </div>
                </template>

            </div>
        </div>

        <div class="w-full max-w-xl flex flex-col items-center space-y-3 pt-2">
            
            <div class="flex items-center justify-between w-full px-4">
                <div>
                    <button x-show="spread === 'pages'" @click="spread = 'index'; isWriting = false" class="px-4 py-2 bg-white/90 border border-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider flex items-center space-x-1.5 transition-all shadow-sm hover:shadow hover:bg-white" x-cloak>
                        <span>⬅</span> <span>Back</span>
                    </button>
                </div>

                <div>
                    <button x-show="spread === 'index'" @click="spread = 'pages'" class="px-4 py-2 bg-white/90 border border-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider flex items-center space-x-1.5 transition-all shadow-sm hover:shadow hover:bg-white">
                        <span>Next</span> <span>➔</span>
                    </button>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="w-full text-center">
                @csrf
                <button type="submit" class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-2xl shadow-md text-xs uppercase tracking-widest transition-all inline-flex items-center space-x-2">
                    <span>Close Journal</span> <span>📕</span>
                </button>
            </form>
            
        </div>

    </div>

</body>
</html>