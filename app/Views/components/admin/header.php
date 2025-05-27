<header class="admin-header">
    <div class="admin-header-content">
        <div class="admin-header-right">
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i>
                    <span>Admin</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="/admin/profile">Profile</a></li>
                    <li><a class="dropdown-item" href="/admin/settings">Settings</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="/logout">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi dropdown header
        const userDropdown = document.getElementById('userDropdown');
        const dropdownMenu = userDropdown.nextElementSibling;
        let isDropdownOpen = false;

        // Fungsi untuk menutup dropdown
        function closeDropdown() {
            dropdownMenu.classList.remove('show');
            isDropdownOpen = false;
        }

        // Event untuk tombol dropdown header
        userDropdown.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            isDropdownOpen = !isDropdownOpen;
            if (isDropdownOpen) {
                dropdownMenu.classList.add('show');
            } else {
                closeDropdown();
            }
        });

        // Event untuk sidebar dropdown
        document.querySelectorAll('.sidebar-dropdown').forEach(function(element) {
            element.addEventListener('click', function(e) {
                // Tutup dropdown header jika terbuka
                if (isDropdownOpen) {
                    closeDropdown();
                }
            });
        });

        // Tutup dropdown ketika klik di luar
        document.addEventListener('click', function(e) {
            if (!userDropdown.contains(e.target)) {
                closeDropdown();
            }
        });
    });
</script>