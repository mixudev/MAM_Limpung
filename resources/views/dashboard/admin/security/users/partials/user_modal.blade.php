<x-app-modal id="userModal" maxWidth="lg" title="Kelola User" description="Formulir data akun pengguna" icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>' iconColor="indigo">
    <form id="userForm" method="POST" action="" class="space-y-4">
        @csrf
        <div id="method-container"></div>

        <!-- Name Field -->
        <div>
            <label for="input_name">Nama Lengkap</label>
            <input type="text" id="input_name" name="name" required placeholder="Masukkan nama lengkap user...">
        </div>

        <!-- Email Field -->
        <div>
            <label for="input_email">Alamat Email</label>
            <input type="email" id="input_email" name="email" required placeholder="name@example.com">
        </div>

        <!-- Password Field -->
        <div>
            <label for="input_password" id="label_password">Password</label>
            <input type="password" id="input_password" name="password" placeholder="Minimal 8 karakter...">
            <p id="password_hint" class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1 hidden">
                * Biarkan kosong jika tidak ingin memperbarui password.
            </p>
        </div>

        <!-- Roles Dropdown -->
        <div>
            <label for="input_role" class="mb-2 block">Peran (Role)</label>
            <select id="input_role" name="roles[]" required class="w-full rounded-none">
                <option value="">Pilih Role User...</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">
                        {{ $role->display_name ?: $role->name }} (Level {{ $role->level }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-zinc-800">
            <button type="button" onclick="AppModal.close('userModal')" class="modal-btn-cancel">
                Batal
            </button>
            <button type="submit" class="modal-btn-primary">
                Simpan Akun
            </button>
        </div>
    </form>
</x-app-modal>

<script>
    const userForm = document.getElementById('userForm');
    const methodContainer = document.getElementById('method-container');
    const inputName = document.getElementById('input_name');
    const inputEmail = document.getElementById('input_email');
    const inputPassword = document.getElementById('input_password');
    const labelPassword = document.getElementById('label_password');
    const passwordHint = document.getElementById('password_hint');
    const inputRole = document.getElementById('input_role');

    // Get prefixes dynamically based on current user role
    const isSuperAdmin = @json(Auth::user()->hasRole('super-admin'));
    const routePrefix = isSuperAdmin ? 'super-admin' : 'admin';

    function openAddUserModal() {
        // Reset Form
        userForm.reset();
        methodContainer.innerHTML = '';
        
        // URL
        userForm.action = `/${routePrefix}/users`;
        
        // Modal Header title
        const modalTitle = document.querySelector('#userModal .modal-header h3');
        if (modalTitle) modalTitle.textContent = 'Tambah User Baru';

        // Password field is required for new users
        inputPassword.required = true;
        labelPassword.textContent = 'Password';
        passwordHint.classList.add('hidden');

        // Reset role dropdown
        inputRole.selectedIndex = 0;

        AppModal.open('userModal');
    }
</script>
