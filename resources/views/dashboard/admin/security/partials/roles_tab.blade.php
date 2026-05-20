<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse text-xs">
        <thead>
            <tr class="border-b border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400">
                <th class="py-3 px-4">Nama Role / Tampilan</th>
                <th class="py-3 px-4">Identifier (System Name)</th>
                <th class="py-3 px-4 text-center">Level Akses</th>
                <th class="py-3 px-4">Deskripsi</th>
                <th class="py-3 px-4 text-center">Total Izin</th>
                <th class="py-3 px-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-zinc-850">
            @foreach ($roles as $role)
                @php
                    $isSystemRole = in_array($role->name, ['super-admin', 'admin', 'guru', 'siswa']);
                @endphp
                <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-900/30 transition-colors">
                    <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-zinc-200">
                        <div class="flex items-center gap-1.5">
                            @if ($isSystemRole)
                                <svg class="w-3.5 h-3.5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Bawaan Sistem (Terkunci)">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            @endif
                            <span>{{ $role->display_name }}</span>
                        </div>
                    </td>
                    <td class="py-3.5 px-4 font-mono text-slate-500 dark:text-zinc-400">
                        {{ $role->name }}
                    </td>
                    <td class="py-3.5 px-4 text-center">
                        <span class="px-2 py-0.5 font-mono text-[10px] font-bold rounded-none bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300">
                            {{ $role->level }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-slate-500 dark:text-zinc-400">
                        {{ $role->description ?: '-' }}
                    </td>
                    <td class="py-3.5 px-4 text-center font-bold text-indigo-600 dark:text-indigo-400 font-mono">
                        {{ $role->permissions->count() }}
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex justify-end items-center gap-2">
                            <!-- Edit Button -->
                            <button type="button" 
                                    onclick="openEditRoleModal({{ json_encode($role) }}, {{ json_encode($role->permissions->pluck('name')) }})"
                                    class="py-1 px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-750 text-slate-700 dark:text-zinc-300 font-mono font-bold text-[10px] uppercase tracking-wider transition-colors">
                                Edit
                            </button>

                            <!-- Delete Button -->
                            @if ($isSystemRole)
                                <button type="button" disabled 
                                        class="py-1 px-2.5 bg-slate-100 dark:bg-zinc-850 text-slate-400 dark:text-zinc-600 font-mono font-bold text-[10px] uppercase tracking-wider cursor-not-allowed"
                                        title="Role bawaan sistem tidak dapat dihapus">
                                    Hapus
                                </button>
                            @else
                                <form id="delete-form-{{ $role->id }}" action="{{ route('super-admin.roles.destroy', $role) }}" method="POST" 
                                      class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            onclick="confirmDeleteRole({{ $role->id }}, '{{ addslashes($role->display_name) }}')"
                                            class="py-1 px-2.5 bg-rose-600 hover:bg-rose-700 text-white font-mono font-bold text-[10px] uppercase tracking-wider transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function confirmDeleteRole(roleId, roleDisplayName) {
        if (window.AppPopup) {
            window.AppPopup.confirm({
                title: 'Hapus Role?',
                description: `Apakah Anda yakin ingin menghapus role '<b>${roleDisplayName}</b>'? Pendaftaran pengguna dengan role ini akan terpengaruh dan tindakan ini tidak dapat dibatalkan.`,
                confirmText: 'Ya, Hapus',
                cancelText: 'Batal',
                onConfirm: () => {
                    const form = document.getElementById('delete-form-' + roleId);
                    if (form) {
                        form.submit();
                    }
                }
            });
        } else {
            if (confirm(`Apakah Anda yakin ingin menghapus role '${roleDisplayName}'?`)) {
                const form = document.getElementById('delete-form-' + roleId);
                if (form) {
                    form.submit();
                }
            }
        }
    }
</script>
