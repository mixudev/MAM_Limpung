<div id="roleModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm transition-opacity" onclick="closeRoleModal()"></div>

    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 w-full max-w-3xl shadow-2xl transition-all rounded-none flex flex-col max-h-[90vh]">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-5 border-b border-slate-100 dark:border-zinc-800">
                <h3 id="modalTitle" class="text-sm font-mono font-bold uppercase tracking-wider text-slate-800 dark:text-zinc-200">
                    Tambah Role Baru
                </h3>
                <button type="button" onclick="closeRoleModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-zinc-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form id="roleForm" method="POST" action="" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div id="formMethodContainer"></div>

                <!-- Form Fields (Scrollable) -->
                <div class="p-6 space-y-6 overflow-y-auto flex-1">
                    
                    <!-- System Role Warning Banner -->
                    <div id="systemRoleBanner" class="hidden bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/40 p-4 text-amber-800 dark:text-amber-400 text-xs rounded-none">
                        <p class="font-bold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Role Bawaan Sistem
                        </p>
                        <p class="mt-1">Nama identifier (`name`) dan tingkat `level` role bawaan sistem tidak dapat diubah demi menjaga integritas logika aplikasi.</p>
                    </div>

                    <!-- Row 1: Name & Display Name -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Identifier (kebab-case) <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" id="roleName" required placeholder="contoh: staf-perpustakaan"
                                   class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Nama Tampilan (Display Name) <span class="text-rose-500">*</span></label>
                            <input type="text" name="display_name" id="roleDisplayName" required placeholder="contoh: Staf Perpustakaan"
                                   class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                        </div>
                    </div>

                    <!-- Row 2: Level & Description -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Hierarchy Level (0 - 100) <span class="text-rose-500">*</span></label>
                            <input type="number" name="level" id="roleLevel" required min="0" max="100" placeholder="contoh: 15"
                                   class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Deskripsi</label>
                            <input type="text" name="description" id="roleDescription" placeholder="Deskripsi tugas dan tanggung jawab role ini..."
                                   class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                        </div>
                    </div>

                    <!-- Permissions Checklist -->
                    <div class="space-y-4">
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 border-b border-slate-100 dark:border-zinc-800 pb-2">
                            Daftar Hak Akses (Permissions)
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($permissionsGrouped as $groupName => $permissions)
                                <div class="border border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-950 p-4">
                                    <div class="flex items-center justify-between mb-3 pb-1.5 border-b border-slate-200/60 dark:border-zinc-800">
                                        <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-slate-600 dark:text-zinc-400">
                                            {{ $groupName }}
                                        </span>
                                        <button type="button" onclick="toggleGroupCheckboxes('{{ $groupName }}')" class="text-[9px] font-mono font-bold text-indigo-600 dark:text-indigo-400 hover:underline uppercase">
                                            Toggle Semua
                                        </button>
                                    </div>
                                    <div class="space-y-2">
                                        @foreach ($permissions as $permission)
                                            <label class="flex items-start gap-2.5 cursor-pointer select-none">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" data-group="{{ $groupName }}"
                                                       class="permission-checkbox rounded-none border-slate-300 dark:border-zinc-700 text-indigo-600 focus:ring-indigo-500/25 mt-0.5" />
                                                <div class="text-xs">
                                                    <span class="font-semibold text-slate-700 dark:text-zinc-300">{{ $permission->display_name ?: $permission->name }}</span>
                                                    <span class="block text-[9px] font-mono text-slate-400 dark:text-zinc-500">{{ $permission->name }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-3 p-5 border-t border-slate-100 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950">
                    <button type="button" onclick="closeRoleModal()" class="py-2 px-4 bg-slate-200 hover:bg-slate-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 font-mono font-bold text-xs uppercase tracking-wider rounded-none transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="py-2 px-5 bg-indigo-600 hover:bg-indigo-700 text-white font-mono font-bold text-xs uppercase tracking-wider rounded-none transition-colors">
                        Simpan Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const roleModal = document.getElementById('roleModal');
    const roleForm = document.getElementById('roleForm');
    const modalTitle = document.getElementById('modalTitle');
    const formMethodContainer = document.getElementById('formMethodContainer');
    const systemRoleBanner = document.getElementById('systemRoleBanner');
    
    const roleName = document.getElementById('roleName');
    const roleDisplayName = document.getElementById('roleDisplayName');
    const roleDescription = document.getElementById('roleDescription');
    const roleLevel = document.getElementById('roleLevel');

    function openAddRoleModal() {
        // Reset form
        roleForm.reset();
        roleForm.action = "{{ route('super-admin.roles.store') }}";
        formMethodContainer.innerHTML = ''; // POST is default
        modalTitle.textContent = "Tambah Role Baru";
        
        // Enable fields
        roleName.readOnly = false;
        roleLevel.readOnly = false;
        roleName.classList.remove('bg-slate-100', 'dark:bg-zinc-800/50', 'cursor-not-allowed');
        roleLevel.classList.remove('bg-slate-100', 'dark:bg-zinc-800/50', 'cursor-not-allowed');
        systemRoleBanner.classList.add('hidden');

        // Uncheck all permissions
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);

        // Show Modal
        roleModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function openEditRoleModal(role, assignedPermissions) {
        // Reset form
        roleForm.reset();
        roleForm.action = "/super-admin/roles/" + role.name;
        formMethodContainer.innerHTML = '@method("PUT")';
        modalTitle.textContent = "Edit Role: " + role.display_name;

        // Populate fields
        roleName.value = role.name;
        roleDisplayName.value = role.display_name;
        roleDescription.value = role.description || '';
        roleLevel.value = role.level;

        // Check if system role
        const systemRoles = ['super-admin', 'admin', 'guru', 'siswa'];
        const isSystem = systemRoles.includes(role.name);
        if (isSystem) {
            roleName.readOnly = true;
            roleLevel.readOnly = true;
            roleName.classList.add('bg-slate-100', 'dark:bg-zinc-800/50', 'cursor-not-allowed');
            roleLevel.classList.add('bg-slate-100', 'dark:bg-zinc-800/50', 'cursor-not-allowed');
            systemRoleBanner.classList.remove('hidden');
        } else {
            roleName.readOnly = false;
            roleLevel.readOnly = false;
            roleName.classList.remove('bg-slate-100', 'dark:bg-zinc-800/50', 'cursor-not-allowed');
            roleLevel.classList.remove('bg-slate-100', 'dark:bg-zinc-800/50', 'cursor-not-allowed');
            systemRoleBanner.classList.add('hidden');
        }

        // Check assigned permissions
        document.querySelectorAll('.permission-checkbox').forEach(cb => {
            cb.checked = assignedPermissions.includes(cb.value);
        });

        // Show Modal
        roleModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeRoleModal() {
        roleModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function toggleGroupCheckboxes(groupName) {
        const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${groupName}"]`);
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        checkboxes.forEach(cb => cb.checked = !allChecked);
    }

    // Smart kebab-case validator for role name input
    roleName.addEventListener('input', function() {
        let value = this.value;
        // Replace spaces with hyphens
        value = value.replace(/\s+/g, '-');
        // Remove any character that is not a letter, digit, or hyphen
        value = value.replace(/[^a-zA-Z0-9\-]/g, '');
        // Convert to lowercase
        this.value = value.toLowerCase();
    });
</script>
