<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yannie's Secret Diary 📖</title>
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
<body class="bg-gradient-to-tr from-rose-100 via-pink-50 to-white min-h-screen flex flex-col items-center justify-center p-4 select-none" 
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
                  // FIX: Match words OR single newlines so stanzas aren't deleted
                  let tokens = entry.body.match(/\n|\S+/g) || [];
                  let wordsPerPage = 170; 
                  let chunkCount = Math.ceil(tokens.length / wordsPerPage) || 1;

                  for (let i = 0; i < chunkCount; i++) {
                      let start = i * wordsPerPage;
                      let end = start + wordsPerPage;
                      let tokenChunk = tokens.slice(start, end);

                      // Reconstruct the text elegantly while managing spaces around newlines
                      let textChunk = '';
                      tokenChunk.forEach((token, index) => {
                          if (token === '\n') {
                              textChunk += '\n';
                          } else {
                              textChunk += token + (index < tokenChunk.length - 1 && tokenChunk[index+1] !== '\n' ? ' ' : '');
                          }
                      });

                      computedPages.push({
                          entryId: entry.id,
                          pageNumber: globalPageNum++,
                          title: entry.title,
                          mood: entry.mood,
                          date: entry.date,
                          body: textChunk.trim() + (i < chunkCount - 1 ? ' ...' : ''),
                          isContinuation: i > 0
                      });
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
      x-effect="spread || currentPageIndex; $nextTick(() => { if ($refs.bookContainer) $refs.bookContainer.scrollLeft = 0 })">

    <!-- OPEN BOOK SYSTEM -->
    <div class="w-full max-w-5xl flex flex-col items-center space-y-6">
        <p class="md:hidden text-xs text-rose-300 text-center -mb-2">👉 Swipe to see the next page</p>

        <div x-ref="bookContainer" class="book-scroll w-full flex md:block overflow-x-auto md:overflow-visible snap-x snap-mandatory">
            <div class="flex md:grid md:grid-cols-2 md:bg-white md:rounded-[2rem] md:shadow-inner md:min-h-[550px] md:border md:border-rose-500/10 md:p-8 gap-0 md:gap-0 md:divide-x md:divide-rose-200/60 w-full">

                <!-- ================= LEFT PAGE ================= -->
                <div class="w-full min-w-full min-h-[520px] md:min-h-0 md:min-w-0 snap-start snap-always flex-shrink-0 box-border bg-white md:bg-transparent rounded-[2rem] md:rounded-none shadow-2xl md:shadow-none border border-rose-200/60 md:border-0 p-6 md:p-10 flex flex-col justify-between md:bg-gradient-to-l md:from-transparent md:to-rose-50/50">
                    
                    <!-- State 1: Main Welcome Index Left Side -->
                    <div x-show="spread === 'index'" class="h-full flex flex-col justify-between animate-fadeIn">
                        <div>
                            <span class="text-xs font-bold text-rose-400 tracking-wide block mb-6">✨ MY JOURNAL </span>
                            <h3 class="text-2xl font-bold text-slate-800 mb-4">Welcome back, Yannie 🩷</h3>
                            <p class="text-slate-600 text-sm leading-relaxed mb-4">Every day is a new chance to become a better version of yourself.</p>
                        </div>
                    </div>

                    <!-- State 2: Reading Mode (Left Page) -->
                    <div x-show="spread === 'read'" class="h-full flex flex-col justify-between animate-fadeIn" x-cloak>
                        <div x-data="{ leftPage: null }" x-effect="leftPage = pages[currentPageIndex * 2]">
                            <div x-show="leftPage">
                                <span class="text-xs font-bold text-rose-400 tracking-wide block mb-4" x-text="'📖 PAGE ' + String(leftPage?.pageNumber).padStart(2, '0')"></span>
                                <div class="flex items-center space-x-2 border-b border-rose-200 pb-3 mb-4">
                                    <span class="text-2xl" x-text="leftPage?.mood"></span>
                                    <h3 class="text-xl font-bold text-slate-800 truncate" x-text="leftPage?.title"></h3>
                                    <template x-if="leftPage?.isContinuation">
                                        <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-md font-medium ml-2">Cont.</span>
                                    </template>
                                </div>
                                <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line select-text" x-text="leftPage?.body"></p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-2">
                            <span class="text-xs text-rose-300" x-text="pages[currentPageIndex * 2] ? 'Recorded on ' + pages[currentPageIndex * 2]?.date : ''"></span>
                            <div class="flex items-center space-x-3" x-show="leftPage">
                                <button type="button" @click="startEdit(leftPage.entryId)" class="text-xs font-bold text-rose-300 hover:text-rose-500 transition-colors">✏️ Edit</button>
                                <form :action="'/entries/' + leftPage?.entryId" method="POST" @submit.prevent="if(confirm('Delete this entry? This cannot be undone.')) $el.submit()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-red-400 hover:text-red-600 transition-colors">🗑️ Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- State 3: Write Page Form Canvas Left Side -->
                    <div x-show="spread === 'write'" class="w-full text-left h-full flex flex-col justify-between animate-fadeIn" x-cloak>
                        <form action="/entries" method="POST" id="journalForm" class="h-full flex flex-col justify-between flex-1">
                            @csrf
                            <div class="flex flex-col flex-1 space-y-4 mb-4">
                                <div class="flex justify-between items-center border-b pb-1">
                                    <span class="text-xs font-bold text-rose-400 uppercase">New Entry...</span>
                                </div>
                                <input type="text" name="title" required placeholder=" Journal Title..." class="w-full bg-transparent border-b border-rose-200/60 focus:outline-none text-lg font-bold text-slate-800 py-1">
                                <textarea name="content" required placeholder="What's on your mind?" class="w-full bg-transparent resize-none focus:outline-none text-slate-600 text-sm leading-relaxed flex-1 h-full min-h-[180px]"></textarea>
                            </div>
                            
                            <div class="space-y-2 pt-2 border-t border-rose-100">
                                <div class="flex items-center space-x-3">
                                    <label class="text-xs font-bold text-slate-400">Sticker badge:</label>
                                    <select name="mood" class="bg-white border border-rose-200 rounded-xl px-2 py-1 text-xs text-slate-700">
                                        <option value="🌷">🌷</option>
                                        <option value="🩷">🩷</option>
                                        <option value="😽">😽</option>
                                        <option value="😴">😴</option>
                                        <option value="📞">📞</option>
                                        <option value="🎮">🎮</option>
                                        <option value="🍟">🍟</option>
                                        <option value="✨">✨</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- State 4: Edit Entry Form Canvas Left Side -->
                    <div x-show="spread === 'edit'" class="w-full text-left h-full flex flex-col justify-between animate-fadeIn" x-cloak>
                        <form :action="'/entries/' + editingEntry?.id" method="POST" id="editForm" class="h-full flex flex-col justify-between flex-1">
                            @csrf
                            @method('PUT')
                            <div class="flex flex-col flex-1 space-y-4 mb-4">
                                <div class="flex justify-between items-center border-b pb-1">
                                    <span class="text-xs font-bold text-rose-400 uppercase">Edit Entry...</span>
                                </div>
                                <input type="text" name="title" required x-model="editingEntry.title" placeholder=" Journal Title..." class="w-full bg-transparent border-b border-rose-200/60 focus:outline-none text-lg font-bold text-slate-800 py-1">
                                <textarea name="content" required x-model="editingEntry.body" placeholder="What's on your mind?" class="w-full bg-transparent resize-none focus:outline-none text-slate-600 text-sm leading-relaxed flex-1 h-full min-h-[180px]"></textarea>
                            </div>
                            
                            <div class="space-y-2 pt-2 border-t border-rose-100">
                                <div class="flex items-center space-x-3">
                                    <label class="text-xs font-bold text-slate-400">Sticker badge:</label>
                                    <select name="mood" x-model="editingEntry.mood" class="bg-white border border-rose-200 rounded-xl px-2 py-1 text-xs text-slate-700">
                                        <option value="🌷">🌷</option>
                                        <option value="🩷">🩷</option>
                                        <option value="😽">😽</option>
                                        <option value="😴">😴</option>
                                        <option value="📞">📞</option>
                                        <option value="🎮">🎮</option>
                                        <option value="🍟">🍟</option>
                                        <option value="✨">✨</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ================= RIGHT PAGE ================= -->
                <div class="w-full min-w-full min-h-[520px] md:min-h-0 md:min-w-0 snap-start snap-always flex-shrink-0 box-border bg-white md:bg-transparent rounded-[2rem] md:rounded-none shadow-2xl md:shadow-none border border-rose-200/60 md:border-0 p-6 md:p-10 flex flex-col justify-between">
                    
                    <!-- State 1: Table of Contents List Index -->
                    <div x-show="spread === 'index'" class="h-full flex flex-col justify-between animate-fadeIn">
                        <div>
                            <div class="flex justify-between items-baseline mb-6 border-b border-rose-200 pb-3">
                                <h3 class="text-xl font-bold text-slate-800">Index</h3>
                                <span class="text-xs font-bold text-slate-400 uppercase">Chapters ({{ $entries->count() }})</span>
                            </div>
                            <div class="overflow-y-auto max-h-[380px] space-y-2 pr-1">
                                <template x-for="(page, idx) in pages" :key="idx">
                                    <div x-show="!page.isContinuation"
                                         class="flex items-center justify-between p-3 rounded-xl hover:bg-rose-50/70 transition-all border border-transparent hover:border-rose-100/50 group">
                                        
                                        <div @click="spread = 'read'; currentPageIndex = Math.floor(idx / 2)" 
                                             class="flex items-center space-x-3 truncate flex-1 cursor-pointer">
                                            <span class="text-xs font-mono text-rose-300 group-hover:text-rose-400" x-text="'Pg. ' + String(page.pageNumber).padStart(2, '0')"></span>
                                            <span class="text-sm" x-text="page.mood"></span>
                                            <h4 class="text-sm font-semibold text-slate-700 truncate group-hover:text-slate-900" x-text="page.title"></h4>
                                        </div>

                                        <div class="flex items-center space-x-2 ml-2">
                                            <div class="hidden group-hover:flex items-center space-x-2 animate-fadeIn">
                                                <button type="button" 
                                                        @click.stop="startEdit(page.entryId)" 
                                                        class="p-1 text-xs hover:bg-rose-200 rounded transition-colors" 
                                                        title="Edit Entry">✏️</button>
                                                
                                                <form :action="'/entries/' + page?.entryId" 
                                                      method="POST" 
                                                      @submit.prevent="if(confirm('Delete this entry? This cannot be undone.')) $el.submit()">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="p-1 text-xs hover:bg-red-100 rounded text-red-500 transition-colors" 
                                                            title="Delete Entry">🗑️</button>
                                                </form>
                                            </div>
                                            <span class="text-xs font-mono text-rose-300 whitespace-nowrap" x-text="'p. ' + page.date"></span>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="pages.length === 0" class="text-slate-300 text-sm text-center py-16">
                                    Your journal is currently empty. Press Next to start writing.
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end pt-4">
                        </div>
                    </div>

                    <!-- State 2: Reading Mode (Right Page) -->
                    <div x-show="spread === 'read'" class="h-full flex flex-col justify-between animate-fadeIn" x-cloak>
                        <div x-data="{ rightPage: null }" x-effect="rightPage = pages[(currentPageIndex * 2) + 1]">
                            
                            <!-- Display side-by-side if page exists -->
                            <div x-show="rightPage">
                                <span class="text-xs font-bold text-rose-400 tracking-wide block mb-4" x-text="'📖 PAGE ' + String(rightPage?.pageNumber).padStart(2, '0')"></span>
                                <div class="flex items-center space-x-2 border-b border-rose-200 pb-3 mb-4">
                                    <span class="text-2xl" x-text="rightPage?.mood"></span>
                                    <h3 class="text-xl font-bold text-slate-800 truncate" x-text="rightPage?.title"></h3>
                                    <template x-if="rightPage?.isContinuation">
                                        <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-md font-medium ml-2">Cont.</span>
                                    </template>
                                </div>
                                <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line select-text" x-text="rightPage?.body"></p>
                            </div>

                            <!-- Empty right page layout prompt -->
                            <div x-show="!rightPage" class="flex flex-col justify-center items-center border border-dashed border-rose-200 bg-rose-50/40 rounded-2xl p-8 text-center space-y-4 my-4 min-h-[300px]">
                                <span class="text-4xl">✍️</span>
                                <h4 class="text-base font-bold text-slate-800">New Entry Sheet</h4>
                                <p class="text-xs text-slate-400 max-w-[200px]">This side of the book is empty. Press Next to open up your drafting workspace!</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-2">
                            <div class="flex items-center space-x-3" x-show="rightPage">
                                <button type="button" @click="startEdit(rightPage.entryId)" class="text-xs font-bold text-rose-300 hover:text-rose-500 transition-colors">✏️ Edit</button>
                                <form :action="'/entries/' + rightPage?.entryId" method="POST" @submit.prevent="if(confirm('Delete this entry? This cannot be undone.')) $el.submit()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-red-400 hover:text-red-600 transition-colors">🗑️ Delete</button>
                                </form>
                            </div>
                            <span class="text-xs text-rose-300" x-text="pages[(currentPageIndex * 2) + 1] ? 'Recorded on ' + pages[(currentPageIndex * 2) + 1]?.date : ''"></span>
                        </div>
                    </div>

                    <!-- State 3: Write Page Canvas Right Side Confirmation -->
                    <div x-show="spread === 'write'" class="h-full flex flex-col justify-between animate-fadeIn" x-cloak>
                        <div class="flex-1 flex flex-col justify-center items-center border border-dashed border-rose-200 bg-rose-50/40 rounded-2xl p-6 text-center space-y-4">
                            <span class="text-4xl">🔒</span>
                            <h4 class="text-base font-bold text-slate-800">Save this page?</h4>
                            <p class="text-xs text-slate-400 max-w-[200px]">Sure, save na talaga?</p>
                            <button type="submit" form="journalForm" class="py-2.5 px-6 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-sm transition-all">
                                Save to Journal
                            </button>
                        </div>
                    </div>

                    <!-- State 4: Edit Confirmation Right Side -->
                    <div x-show="spread === 'edit'" class="h-full flex flex-col justify-between animate-fadeIn" x-cloak>
                        <div class="flex-1 flex flex-col justify-center items-center border border-dashed border-rose-200 bg-rose-50/40 rounded-2xl p-6 text-center space-y-4">
                            <span class="text-4xl">✏️</span>
                            <h4 class="text-base font-bold text-slate-800">Update this page?</h4>
                            <p class="text-xs text-slate-400 max-w-[200px]">Make sure everything looks right before saving.</p>
                            <button type="submit" form="editForm" class="py-2.5 px-6 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-sm transition-all">
                                Save Changes
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <!-- EXTERNAL NAVIGATION CONTROLS BAR -->
        <div class="w-full max-w-xl flex flex-col items-center space-y-4 pt-2">
            <div class="flex items-center justify-between w-full px-4">
                
                <!-- Pure Back Button -->
                <div>
                    <button x-show="spread === 'write'" 
                            @click="if(pages.length > 0) { spread = 'read'; currentPageIndex = Math.floor((pages.length - 1) / 2) } else { spread = 'index' }" 
                            class="px-5 py-2 bg-white border border-rose-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm hover:scale-105 transition-all">
                        ⬅ Back
                    </button>
                    <button x-show="spread === 'edit'" 
                            @click="spread = 'read'; editingEntry = null" 
                            class="px-5 py-2 bg-white border border-rose-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm hover:scale-105 transition-all">
                        ⬅ Cancel
                    </button>
                    <button x-show="spread === 'read' && currentPageIndex > 0" 
                            @click="currentPageIndex--" 
                            class="px-5 py-2 bg-white border border-rose-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm hover:scale-105 transition-all">
                        ⬅ Back
                    </button>
                    <button x-show="spread === 'read' && currentPageIndex === 0" 
                            @click="spread = 'index'" 
                            class="px-5 py-2 bg-white border border-rose-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm hover:scale-105 transition-all">
                        ⬅ Back
                    </button>
                </div>

                <!-- Pure Next Button -->
                <div>
                    <button x-show="spread === 'index'" 
                            @click="if(pages.length > 0) { spread = 'read'; currentPageIndex = 0 } else { spread = 'write' }" 
                            class="px-5 py-2 bg-white border border-rose-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm hover:scale-105 transition-all">
                        Next ➔
                    </button>
                    <button x-show="spread === 'read' && ((currentPageIndex + 1) * 2) < pages.length" 
                            @click="currentPageIndex++" 
                            class="px-5 py-2 bg-white border border-rose-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm hover:scale-105 transition-all">
                        Next ➔
                    </button>
                    <button x-show="spread === 'read' && ((currentPageIndex + 1) * 2) >= pages.length" 
                            @click="spread = 'write'" 
                            class="px-5 py-2 bg-rose-500 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-md hover:bg-rose-600 transition-all">
                        Next ➔
                    </button>
                </div>

            </div>

            <!-- Logout component footer -->
            <form action="{{ route('logout') }}" method="POST" class="w-full text-center pt-2">
                @csrf
                <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-2xl shadow-md text-xs uppercase tracking-widest transition-all">Close Journal 📕</button>
            </form>
        </div>
    </div>

</body>
</html>