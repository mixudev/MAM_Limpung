<!-- 3. TAB: SOCIAL MEDIA -->
<div id="tab-social" class="tab-content hidden space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Link Facebook</label>
            <input type="url" name="facebook_url" value="{{ old('facebook_url', $siteSetting->facebook_url) }}" placeholder="https://facebook.com/nama-sekolah"
                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
        </div>

        <div>
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Link Instagram</label>
            <input type="url" name="instagram_url" value="{{ old('instagram_url', $siteSetting->instagram_url) }}" placeholder="https://instagram.com/nama-sekolah"
                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
        </div>

        <div>
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Link YouTube Channel</label>
            <input type="url" name="youtube_url" value="{{ old('youtube_url', $siteSetting->youtube_url) }}" placeholder="https://youtube.com/c/nama-channel"
                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
        </div>

        <div>
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Link Twitter / X</label>
            <input type="url" name="twitter_url" value="{{ old('twitter_url', $siteSetting->twitter_url) }}" placeholder="https://x.com/nama-sekolah"
                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
        </div>
    </div>
</div>
