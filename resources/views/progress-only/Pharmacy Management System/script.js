const initialUsers = [
    { id: 1, name: "John Smith", email: "john@pharmaease.com", role: "Admin", status: "Active", lastLogin: "2025-10-20 09:30" },
    { id: 2, name: "Sarah Johnson", email: "sarah@pharmaease.com", role: "Pharmacist", status: "Active", lastLogin: "2025-10-20 08:15" },
    { id: 3, name: "Mike Davis", email: "mike@pharmaease.com", role: "Cashier", status: "Active", lastLogin: "2025-10-19 17:45" },
    { id: 4, name: "Emily Brown", email: "emily@pharmaease.com", role: "Pharmacist", status: "Active", lastLogin: "2025-10-19 16:20" },
    { id: 5, name: "Robert Wilson", email: "robert@pharmaease.com", role: "Cashier", status: "Inactive", lastLogin: "2025-10-15 14:10" },
];

let users = [...initialUsers];
let editingUser = null;

const userTableBody = document.getElementById('user-table-body');
const searchInput = document.getElementById('search-term');
const modal = document.getElementById('user-form-modal');
const modalTitle = document.getElementById('modal-title');
const formSubmitButton = document.getElementById('form-submit-button');
const statusField = document.getElementById('status-field');

function getRoleBadge(role) {
    let className = '';
    if (role === 'Admin') {
        className = 'badge-role-admin';
    } else if (role === 'Pharmacist') {
        className = 'badge-role-pharmacist';
    } else if (role === 'Cashier') {
        className = 'badge-role-cashier';
    }
    return `<span class="badge-base ${className}">${role}</span>`;
}

function getStatusBadge(status) {
    if (status === 'Active') {
        return `<span class="badge-base badge-status-active">Active</span>`;
    } else {
        return `<span class="badge-base badge-status-inactive">Inactive</span>`;
    }
}

function handleDeleteUser(id) {
    if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
        users = users.filter(user => user.id !== id);
        renderTable();
    }
}

function openFormModal(user) {
    editingUser = user;
    const form = document.getElementById('user-form');
    form.reset();

    if (user) {
        modalTitle.textContent = 'Edit User: ' + user.name;
        formSubmitButton.textContent = 'Simpan Perubahan';
        document.getElementById('user-id').value = user.id;
        document.getElementById('user-name').value = user.name;
        document.getElementById('user-email').value = user.email;
        document.getElementById('user-role').value = user.role;
        document.getElementById('user-status').value = user.status;
        statusField.classList.remove('hidden');
    } else {
        modalTitle.textContent = 'Tambah Pengguna Baru';
        formSubmitButton.textContent = 'Tambah Pengguna';
        document.getElementById('user-id').value = '';
        document.getElementById('user-status').value = 'Active'; 
        statusField.classList.add('hidden');
    }
    modal.classList.remove('hidden');
}

function closeFormModal(event = null) {
    if (event && event.target.id === 'user-form-modal') {
        modal.classList.add('hidden');
    } else if (event === null || !event) {
        modal.classList.add('hidden');
    }
}

function handleFormSubmit(event) {
    event.preventDefault();

    const id = document.getElementById('user-id').value;
    const name = document.getElementById('user-name').value;
    const email = document.getElementById('user-email').value;
    const role = document.getElementById('user-role').value;
    const status = document.getElementById('user-status').value;

    const userData = {
        name,
        email,
        role,
        status,
    };

    if (id) {
        users = users.map(user => 
            user.id === parseInt(id) ? { 
                ...user, 
                ...userData,
                status: status, 
            } : user
        );
    } else {
        
        const newUser = {
            id: users.length > 0 ? Math.max(...users.map(u => u.id)) + 1 : 1,
            ...userData,
            status: 'Active', 
            lastLogin: 'Never',
        };
        users.push(newUser);
    }

    closeFormModal();
    renderTable();
}

function handleSearch(term) {
    renderTable(term);
}

function renderTable(searchTerm = searchInput.value) {
    const lowerCaseTerm = searchTerm.toLowerCase();
    const filteredUsers = users.filter(user =>
        user.name.toLowerCase().includes(lowerCaseTerm) ||
        user.email.toLowerCase().includes(lowerCaseTerm) ||
        user.role.toLowerCase().includes(lowerCaseTerm)
    );

    userTableBody.innerHTML = ''; 

    const noUsersMessage = document.getElementById('no-users-message');
    if (filteredUsers.length === 0) {
        noUsersMessage.classList.remove('hidden');
        noUsersMessage.textContent = 'Tidak ada pengguna yang ditemukan sesuai dengan istilah pencarian Anda.';
    } else {
        noUsersMessage.classList.add('hidden');
    }


    filteredUsers.forEach(user => {
        const row = document.createElement('tr');
        row.className = 'table-row-hover'; 
        row.innerHTML = `
            <td class="table-body-cell font-medium">${user.name}</td>
            <td class="table-body-cell text-muted-foreground">${user.email}</td>
            <td class="table-body-cell">${getRoleBadge(user.role)}</td>
            <td class="table-body-cell">${getStatusBadge(user.status)}</td>
            <td class="table-body-cell text-muted-foreground">${user.lastLogin}</td>
            <td class="table-body-cell">
                <div class="action-buttons-group">
                    <button class="btn-base btn-ghost action-button-icon text-primary" onclick="openFormModal(${JSON.stringify(user).replace(/"/g, '&quot;')})">
                        <i data-lucide="edit" class="h-4 w-4"></i>
                    </button>
                    <button class="btn-base btn-ghost action-button-icon text-destructive" onclick="handleDeleteUser(${user.id})">
                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                    </button>
                </div>
            </td>
        `;
        userTableBody.appendChild(row);
    });

    
    lucide.createIcons();
}

window.onload = function() {
    renderTable();
    lucide.createIcons();
}