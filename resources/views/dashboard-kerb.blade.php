<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Secret Journal 🖤</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Fredoka', sans-serif; } 
        [x-cloak] { display: none !important; }
        .animate-fadeIn { animation: fadeIn 0.3s ease-in-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
        .book-scroll::-webkit-scrollbar { display: none; }
        .book-scroll { -ms-overflow-style: none; scrollbar-width: none; scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-gradient-to-tr from-zinc-200 via-stone-100 to-neutral-200 min-h-screen flex flex-col items-center justify-center p-4 select-none" 
      x-data="{ 
          spread: 'index', 
          currentPageIndex: 0, 
          pages: [], 
          rawEntries: {{ Js::from($entries->sortBy('created_at')->values()->map(function($e) {
              return [
                  'id' => $e->id,
                  'title' => $e->title,
                  'body' => $e->body,
                  'mood' => $e->mood,
                  'date' => $e->created_at->format('m/d'),
              ];
          })) }},
          editingEntry: null,
          totalEntries: {{ $entries->count() }},
          
          paginateBook() {
              let computedPages = [];
              let globalPageNum = 1;

              this.rawEntries.forEach(entry => {
                  let tokens = entry.body.match(/\n+|\S+/g) || [];
                  
                  // FIXED: Changed budget to a safe 65 words per page to stop any bottom clipping on mobile
                  let maxWordsPerPage = 85; 
                  
                  let currentChunk = [];
                  let wordsOnThisPage = 0;

                  for (let i = 0; i < tokens.length; i++) {
                      let token = tokens[i];
                      currentChunk.push(token);

                      if (!token.includes('\n')) {
                          wordsOnThisPage++;
                      }

                      if (wordsOnThisPage >= maxWordsPerPage || i === tokens.length - 1) {
                          let pageText = '';
                          currentChunk.forEach((t, idx) => {
                              if (t.includes('\n')) {
                                  pageText += t;
                              } else {
                                  pageText += t + (idx < currentChunk.length - 1 && !currentChunk[idx + 1].includes('\n') ? ' ' : '');
                              }
                          });

                          computedPages.push({
                              entryId: entry.id,
                              pageNumber: globalPageNum++,
                              title: entry.title,
                              mood: entry.mood,
                              date: entry.date,
                              body: pageText.trim(),
                              isContinuation: computedPages.length > 0 && computedPages[computedPages.length - 1].entryId === entry.id
                          });

                          currentChunk = [];
                          wordsOnThisPage = 0;
                      }
                  }
              });

              this.pages = computedPages;
          },

          startEdit(entryId) {
              this.editingEntry = JSON.parse(JSON.stringify(this.rawEntries.find(e => e.id === entryId)));
              this.spread = 'edit';
          }
      }" 
      x-init="paginateBook()"
      x-effect="spread; currentPageIndex; $nextTick(() => { if ($refs.bookContainer) $refs.bookContainer.scrollLeft = 0 })">

    <div class="w-full max-w-5xl flex flex-col items-center space-y-6">
        <p class="md:hidden text-xs text-zinc-400 text-center -mb-2">👉 Swipe to see the next page</p>

        <div x-ref="bookContainer" class="book-scroll w-full flex md:block overflow-x-auto md:overflow-visible snap-x snap-mandatory">
            <div class="flex md:grid md:grid-cols-2 md:bg-[#fafafa] md:rounded-[2rem] md:shadow-inner md:min-h-[620px] md:border md:border-zinc-900/10 md:p-8 gap-0 md:gap-0 md:divide-x md:divide-zinc-200 w-full">

                <div class="w-full min-w-full h-[600px] md:h-auto overflow-hidden snap-start snap-always flex-shrink-0 box-border bg-[#fafafa] md:bg-transparent rounded-[2rem] md:rounded-none shadow-2xl md:shadow-none border border-zinc-900/10 md:border-0 p-6 md:p-10 flex flex-col justify-between md:bg-gradient-to-l md:from-transparent md:to-zinc-50">
                    
                    <div x-show="spread === 'index'" class="h-full flex flex-col justify-between animate-fadeIn">
                        <div>
                            <span class="text-xs font-bold text-zinc-400 tracking-wide block mb-6">🖤 MY JOURNAL</span>
                            <h3 class="text-2xl font-bold text-zinc-800 mb-4">Welcome back, Kerby! 🪐</h3>
                            <p class="text-zinc-500 text-sm leading-relaxed mb-4">You're here once again Kerby! Or is it you my baby Yannie? What do you have in mind???</p>
                        </div>
                        <div class="text-xs text-zinc-400">My thoughts? ✨</div>
                    </div>

                    <div x-show="spread === 'read'" class="h-full flex flex-col justify-between animate-fadeIn" x-cloak>
                        <div class="flex-1 flex flex-col justify-between overflow-hidden" x-data="{ leftPage: null }" x-effect="leftPage = pages[currentPageIndex * 2]">
                            <div x-show="leftPage">
                                <span class="text-xs font-bold text-zinc-400 tracking-wide block mb-4" x-text="'📖 PAGE ' + String(leftPage?.pageNumber).padStart(2, '0')"></span>
                                <div class="flex items-center space-x-2 border-b border-zinc-200 pb-3 mb-4">
                                    <span class="text-2xl" x-text="leftPage?.mood"></span>
                                    <h3 class="text-xl font-bold text-zinc-800 truncate" x-text="leftPage?.title"></h3>
                                    <template x-if="leftPage?.isContinuation">
                                        <span class="text-xs bg-zinc-100 text-zinc-700 px-2 py-0.5 rounded-md font-medium ml-2">Cont.</span>
                                    </template>
                                </div>
                                <p class="text-zinc-600 text-sm leading-relaxed whitespace-pre-line select-text" x-text="leftPage?.body"></p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-4 mt-2 border-t border-zinc-100/60 bg-[#fafafa] md:bg-transparent">
                            <span class="text-xs text-zinc-400" x-text="pages[currentPageIndex * 2] ? 'Logged on ' + pages[currentPageIndex * 2]?.date : ''"></span>
                            <div class="flex items-center space-x-3" x-show="leftPage">
                                <button type="button" @click="startEdit(leftPage.entryId)" class="text-xs font-bold text-zinc-400 hover:text-zinc-700 transition-colors">✏️ Edit</button>
                                <form :action="'/entries/' + leftPage?.entryId" method="POST" @submit.prevent="if(confirm('Delete this entry? This cannot be undone.')) $el.submit()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-rose-400 hover:text-rose-600 transition-colors">🗑️ Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div x-show="spread === 'write'" class="w-full text-left h-full flex flex-col justify-between animate-fadeIn" x-cloak>
                        <form action="/entries" method="POST" id="journalForm" class="h-full flex flex-col justify-between flex-1">
                            @csrf
                            <div class="flex flex-col flex-1 space-y-4 mb-4">
                                <div class="flex justify-between items-center border-b pb-1">
                                    <span class="text-xs font-bold text-zinc-400 uppercase">New Entry...</span>
                                </div>
                                <input type="text" name="title" required placeholder="Journal Title..." class="w-full bg-transparent border-b border-zinc-200 focus:outline-none text-lg font-bold text-zinc-800 py-1">
                                <textarea name="content" required placeholder="What's on your mind? My baby!" class="w-full bg-transparent resize-none focus:outline-none text-zinc-600 text-sm leading-relaxed flex-1 h-full"></textarea>
                            </div>
                            
                            <div class="space-y-2 pt-2 border-t border-zinc-100">
                                <div class="flex items-center space-x-3">
                                    <label class="text-xs font-bold text-zinc-400">Sticker Badge:</label>
                                    <select name="mood" class="bg-white border border-zinc-200 rounded-xl px-2 py-1 text-xs text-zinc-700 focus:outline-none">
                                        <option value="🖤">🖤</option>
                                        <option value="🎮">🎮</option>
                                        <option value="😴">😴</option>
                                        <option value="🌷">🌷</option>
                                        <option value="🐱">🐱</option>
                                        <option value="📞">📞</option>
                                        <option value="👩‍❤️‍👨">👩‍❤️‍👨</option>
                                        <option value="🛐">🛐</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div x-show="spread === 'edit'" class="w-full text-left h-full flex flex-col justify-between animate-fadeIn" x-cloak>
                        <form :action="'/entries/' + editingEntry?.id" method="POST" id="editForm" class="h-full flex flex-col justify-between flex-1">
                            @csrf
                            @method('PUT')
                            <div class="flex flex-col flex-1 space-y-4 mb-4">
                                <div class="flex justify-between items-center border-b pb-1">
                                    <span class="text-xs font-bold text-zinc-400 uppercase">Edit Entry...</span>
                                </div>
                                <input type="text" name="title" required x-model="editingEntry.title" placeholder="Journal Title..." class="w-full bg-transparent border-b border-zinc-200 focus:outline-none text-lg font-bold text-zinc-800 py-1">
                                <textarea name="content" required x-model="editingEntry.body" placeholder="What's on your mind? My baby!" class="w-full bg-transparent resize-none focus:outline-none text-zinc-600 text-sm leading-relaxed flex-1 h-full"></textarea>
                            </div>
                            
                            <div class="space-y-2 pt-2 border-t border-zinc-100">
                                <div class="flex items-center space-x-3">
                                    <label class="text-xs font-bold text-zinc-400">Sticker Badge:</label>
                                    <select name="mood" x-model="editingEntry.mood" class="bg-white border border-zinc-200 rounded-xl px-2 py-1 text-xs text-zinc-700 focus:outline-none">
                                        <option value="🖤">🖤</option>
                                        <option value="🎮">🎮</option>
                                        <option value="😴">😴</option>
                                        <option value="🌷">🌷</option>
                                        <option value="🐱">🐱</option>
                                        <option value="📞">📞</option>
                                        <option value="👩‍❤️‍👨">👩‍❤️‍👨</option>
                                        <option value="🛐">🛐</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="w-full min-w-full h-[600px] md:h-auto overflow-hidden snap-start snap-always flex-shrink-0 box-border bg-[#fafafa] md:bg-transparent rounded-[2rem] md:rounded-none shadow-2xl md:shadow-none border border-zinc-900/10 md:border-0 p-6 md:p-10 flex flex-col justify-between">
                    
                    <div x-show="spread === 'index'" class="h-full flex flex-col justify-between animate-fadeIn">
                        <div>
                            <div class="flex justify-between items-baseline mb-6 border-b border-zinc-200 pb-3">
                                <h3 class="text-xl font-bold text-zinc-800">Index</h3>
                                <span class="text-xs font-bold text-zinc-400 uppercase">Chapters ({{ $entries->count() }})</span>
                            </div>
                            <div class="overflow-y-auto max-h-[380px] space-y-2 pr-1">
                                <template x-for="(page, idx) in pages" :key="idx">
                                    <div x-show="!page.isContinuation"
                                         class="flex items-center justify-between p-3 rounded-xl hover:bg-zinc-100 transition-all border border-transparent hover:border-zinc-200 group">
                                        
                                        <div @click="spread = 'read'; currentPageIndex = Math.floor(idx / 2)" 
                                             class="flex items-center space-x-3 truncate flex-1 cursor-pointer">
                                            <span class="text-xs font-mono text-zinc-400 group-hover:text-zinc-600" x-text="'Pg. ' + String(page.pageNumber).padStart(2, '0')"></span>
                                            <span class="text-sm" x-text="page.mood"></span>
                                            <h4 class="text-sm font-semibold text-zinc-700 truncate group-hover:text-zinc-900" x-text="page.title"></h4>
                                        </div>

                                        <div class="flex items-center space-x-2 ml-2">
                                            <div class="hidden group-hover:flex items-center space-x-2 animate-fadeIn">
                                                <button type="button" 
                                                        @click.stop="startEdit(page.entryId)" 
                                                        class="p-1 text-xs hover:bg-zinc-200 rounded transition-colors" 
                                                        title="Edit Entry">✏️</button>
                                                
                                                <form :action="'/entries/' + page?.entryId" 
                                                      method="POST" 
                                                      @submit.prevent="if(confirm('Delete this entry? This cannot be undone.')) $el.submit()">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="p-1 text-xs hover:bg-rose-100 rounded text-rose-500 transition-colors" 
                                                            title="Delete Entry">🗑️</button>
                                                </form>
                                            </div>
                                            <span class="text-xs font-mono text-zinc-400 whitespace-nowrap" x-text="'p. ' + page.date"></span>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="pages.length === 0" class="text-zinc-400 text-sm text-center py-16">
                                    Your journal is empty. Press Next to write your first log.
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end pt-4">                        </div>
                    </div>

                    <div x-show="spread === 'read'" class="h-full flex flex-col justify-between animate-fadeIn" x-cloak>
                        <div class="flex-1 flex flex-col justify-between overflow-hidden" x-data="{ rightPage: null }" x-effect="rightPage = pages[(currentPageIndex * 2) + 1]">
                            
                            <div x-show="rightPage">
                                <span class="text-xs font-bold text-zinc-400 tracking-wide block mb-4" x-text="'📖 PAGE ' + String(rightPage?.pageNumber).padStart(2, '0')"></span>
                                <div class="flex items-center space-x-2 border-b border-zinc-200 pb-3 mb-4">
                                    <span class="text-2xl" x-text="rightPage?.mood"></span>
                                    <h3 class="text-xl font-bold text-slate-800 truncate" x-text="rightPage?.title"></h3>
                                    <template x-if="rightPage?.isContinuation">
                                        <span class="text-xs bg-zinc-100 text-zinc-700 px-2 py-0.5 rounded-md font-medium ml-2">Cont.</span>
                                    </template>
                                </div>
                                <p class="text-zinc-600 text-sm leading-relaxed whitespace-pre-line select-text" x-text="rightPage?.body"></p>
                            </div>

                            <div x-show="!rightPage" class="flex flex-col justify-center items-center border border-dashed border-zinc-300 bg-zinc-50/50 rounded-2xl p-8 text-center space-y-4 my-2 flex-1">
                                <span class="text-4xl">✒️</span>
                                <h4 class="text-base font-bold text-zinc-800">Clean Slate</h4>
                                <p class="text-xs text-zinc-400 max-w-[200px]">If you come across this clean slate, go to the next page to write a new one, baby!</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-4 mt-2 border-t border-zinc-100/60 bg-[#fafafa] md:bg-transparent">
                            <div class="flex items-center space-x-3" x-show="rightPage">
                                <button type="button" @click="startEdit(rightPage.entryId)" class="text-xs font-bold text-zinc-400 hover:text-zinc-700 transition-colors">✏️ Edit</button>
                                <form :action="'/entries/' + rightPage?.entryId" method="POST" @submit.prevent="if(confirm('Delete this entry? This cannot be undone.')) $el.submit()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-rose-400 hover:text-rose-600 transition-colors">🗑️ Delete</button>
                                </form>
                            </div>
                            <span class="text-xs text-zinc-400" x-text="pages[(currentPageIndex * 2) + 1] ? 'Logged on ' + pages[(currentPageIndex * 2) + 1]?.date : ''"></span>
                        </div>
                    </div>

                    <div x-show="spread === 'write'" class="h-full flex flex-col justify-between animate-fadeIn" x-cloak>
                        <div class="flex-1 flex flex-col justify-center items-center border border-dashed border-zinc-300 bg-zinc-50/50 rounded-2xl p-6 text-center space-y-4 h-full">
                            <span class="text-4xl">🔒</span>
                            <h4 class="text-base font-bold text-zinc-800">Save this page?</h4>
                            <p class="text-xs text-zinc-400 max-w-[200px]">Sure, save na talaga?</p>
                            <button type="submit" form="journalForm" class="py-2.5 px-6 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-md transition-all">
                                Save to Journal
                            </button>
                        </div>
                    </div>

                    <div x-show="spread === 'edit'" class="h-full flex flex-col justify-between animate-fadeIn" x-cloak>
                        <div class="flex-1 flex flex-col justify-center items-center border border-dashed border-zinc-300 bg-zinc-50/50 rounded-2xl p-6 text-center space-y-4 h-full">
                            <span class="text-4xl">✏️</span>
                            <h4 class="text-base font-bold text-zinc-800">Update this page?</h4>
                            <p class="text-xs text-zinc-400 max-w-[200px]">Make sure everything looks right before saving.</p>
                            <button type="submit" form="editForm" class="py-2.5 px-6 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-md transition-all">
                                Save Changes
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="w-full max-w-xl flex flex-col items-center space-y-4 pt-2">
            <div class="flex items-center justify-between w-full px-4">
                
                <div>
                    <button x-show="spread === 'write'" 
                            @click="if(pages.length > 0) { spread = 'read'; currentPageIndex = Math.floor((pages.length - 1) / 2) } else { spread = 'index' }" 
                            class="px-5 py-2 bg-white border border-zinc-300 text-zinc-700 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm hover:bg-zinc-50 transition-all">
                        ⬅ Back
                    </button>
                    <button x-show="spread === 'edit'" 
                            @click="spread = 'read'; editingEntry = null" 
                            class="px-5 py-2 bg-white border border-zinc-300 text-zinc-700 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm hover:bg-zinc-50 transition-all">
                        ⬅ Cancel
                    </button>
                    <button x-show="spread === 'read' && currentPageIndex > 0" 
                            @click="currentPageIndex--" 
                            class="px-5 py-2 bg-white border border-zinc-300 text-zinc-700 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm hover:bg-zinc-50 transition-all">
                        ⬅ Back
                    </button>
                    <button x-show="spread === 'read' && currentPageIndex === 0" 
                            @click="spread = 'index'" 
                            class="px-5 py-2 bg-white border border-zinc-300 text-zinc-700 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm hover:bg-zinc-50 transition-all">
                        ⬅ Back
                    </button>
                </div>

                <div>
                    <button x-show="spread === 'index'" 
                            @click="if(pages.length > 0) { spread = 'read'; currentPageIndex = 0 } else { spread = 'write' }" 
                            class="px-5 py-2 bg-white border border-zinc-300 text-zinc-700 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm hover:bg-zinc-50 transition-all">
                        Next ➔
                    </button>
                    <button x-show="spread === 'read' && ((currentPageIndex + 1) * 2) < pages.length" 
                            @click="currentPageIndex++" 
                            class="px-5 py-2 bg-white border border-zinc-300 text-zinc-700 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm hover:bg-zinc-50 transition-all">
                        Next ➔
                    </button>
                    <button x-show="spread === 'read' && ((currentPageIndex + 1) * 2) >= pages.length" 
                            @click="spread = 'write'" 
                            class="px-5 py-2 bg-zinc-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-md hover:bg-zinc-800 transition-all">
                        Next ➔
                    </button>
                </div>

            </div>

            <form action="{{ route('logout') }}" method="POST" class="w-full text-center pt-2">
                @csrf
                <button type="submit" class="px-6 py-2.5 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-2xl shadow-md text-xs uppercase tracking-widest transition-all">Close Journal 📕</button>
            </form>
        </div>
    </div>

</body>
</html>