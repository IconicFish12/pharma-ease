const initialSuppliers = [
    { id: 1, name: "MediSupply Co.", contactPerson: "David Lee", email: "david@medisupply.com", phone: "+1-555-0101", address: "123 Medical Ave, NY", medicineCount: 145, status: "Active" },
    { id: 2, name: "PharmaCorp Ltd.", contactPerson: "Lisa Chen", email: "lisa@pharmacorp.com", phone: "+1-555-0102", address: "456 Health St, CA", medicineCount: 98, status: "Active" },
    { id: 3, name: "HealthDist Inc.", contactPerson: "James Wilson", email: "james@healthdist.com", phone: "+1-555-0103", address: "789 Wellness Rd, TX", medicineCount: 203, status: "Active" },
    { id: 4, name: "Global Pharma", contactPerson: "Anna Martinez", email: "anna@globalpharma.com", phone: "+1-555-0104", address: "321 Care Blvd, FL", medicineCount: 67, status: "Active" },
    { id: 5, name: "MedSource Direct", contactPerson: "Tom Harris", email: "tom@medsource.com", phone: "+1-555-0105", address: "654 Cure Lane, IL", medicineCount: 42, status: "Inactive" },
];

let suppliers = [...initialSuppliers];
let editingSupplier = null;

// DOM Elements
const supplierTableBody = document.getElementById('supplier-table-body');
const searchInput = document.getElementById('search-term');
const modal = document.getElementById('form-modal');
const modalTitle = document.getElementById('modal-title');
const formSubmitButton = document.getElementById('form-submit-button');
const supplierForm = document.getElementById('supplier-form');

// Helper function to create status badge HTML
function getStatusBadge(status) {
    if (status === 'Active') {
        return `<span class="badge-base border border-green-500 text-green-600 bg-green-50/50">Active</span>`;
    } else {
        return `<span class="badge-base border border-gray-400 text-gray-600 bg-gray-200/50">Inactive</span>`;
    }
}

// Action Handlers
function openFormModal(supplier) {
    editingSupplier = supplier;
    supplierForm.reset();

    if (supplier) {
        // Edit Mode
        modalTitle.textContent = 'Edit Supplier: ' + supplier.name;
        formSubmitButton.textContent = 'Save Changes';
        document.getElementById('supplier-id').value = supplier.id;
        document.getElementById('supplier-name').value = supplier.name;
        document.getElementById('contact-person').value = supplier.contactPerson;
        document.getElementById('supplier-email').value = supplier.email;
        document.getElementById('supplier-phone').value = supplier.phone;
        document.getElementById('supplier-address').value = supplier.address;
        document.getElementById('supplier-status').value = supplier.status;
    } else {
        // Add Mode
        modalTitle.textContent = 'Add New Supplier';
        formSubmitButton.textContent = 'Add Supplier';
        document.getElementById('supplier-id').value = '';
        document.getElementById('supplier-status').value = 'Active'; 
    }
    modal.classList.remove('hidden');
}

function closeFormModal(event = null) {
    if (event && event.target.id === 'form-modal') {
        modal.classList.add('hidden');
    } else if (event === null || !event) {
        modal.classList.add('hidden');
    }
}

function handleFormSubmit(event) {
    event.preventDefault();

    const id = document.getElementById('supplier-id').value;
    const name = document.getElementById('supplier-name').value;
    const contactPerson = document.getElementById('contact-person').value;
    const email = document.getElementById('supplier-email').value;
    const phone = document.getElementById('supplier-phone').value;
    const address = document.getElementById('supplier-address').value;
    const status = document.getElementById('supplier-status').value;

    const supplierData = {
        name,
        contactPerson,
        email,
        phone,
        address,
        status,
    };

    if (id) {
        // Edit existing supplier
        suppliers = suppliers.map(supplier => 
            supplier.id === parseInt(id) ? { 
                ...supplier, 
                ...supplierData,
                medicineCount: supplier.medicineCount 
            } : supplier
        );
    } else {
        // Add new supplier
        const newSupplier = {
            id: suppliers.length > 0 ? Math.max(...suppliers.map(s => s.id)) + 1 : 1,
            ...supplierData,
            medicineCount: 0, 
        };
        suppliers.push(newSupplier);
    }

    closeFormModal();
    renderTable();
}

function handleSearch(term) {
    renderTable(term);
}

function handleViewCatalog(supplierName) {
    console.log(`Melihat Katalog untuk ${supplierName}.`);
    // NOTE: Di lingkungan nyata, ini akan membuka halaman detail inventaris yang terkait dengan pemasok ini.
}


// Rendering Function
function renderTable(searchTerm = searchInput.value) {
    const lowerCaseTerm = searchTerm.toLowerCase();
    const filteredSuppliers = suppliers.filter(supplier =>
        supplier.name.toLowerCase().includes(lowerCaseTerm) ||
        supplier.contactPerson.toLowerCase().includes(lowerCaseTerm) ||
        supplier.email.toLowerCase().includes(lowerCaseTerm)
    );

    supplierTableBody.innerHTML = ''; 

    if (filteredSuppliers.length === 0) {
        document.getElementById('no-suppliers-message').classList.remove('hidden');
        document.getElementById('no-suppliers-message').textContent = 'Tidak ada pemasok yang ditemukan sesuai dengan istilah pencarian Anda.';
    } else {
        document.getElementById('no-suppliers-message').classList.add('hidden');
    }


    filteredSuppliers.forEach(supplier => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-muted/50 transition-colors';
        row.innerHTML = `
            <td class="p-4 align-middle font-medium">${supplier.name}</td>
            <td class="p-4 align-middle text-muted-foreground">${supplier.contactPerson}</td>
            <td class="p-4 align-middle text-muted-foreground">${supplier.email}</td>
            <td class="p-4 align-middle text-muted-foreground">${supplier.phone}</td>
            <td class="p-4 align-middle text-muted-foreground">${supplier.address}</td>
            <td class="p-4 align-middle">
                <span class="badge-base border border-primary text-primary bg-primary/10">
                    ${supplier.medicineCount} items
                </span>
            </td>
            <td class="p-4 align-middle">${getStatusBadge(supplier.status)}</td>
            <td class="p-4 align-middle">
                <div class="flex gap-1">
                    <button class="btn-base btn-ghost p-2 h-8 w-8 text-primary" onclick="openFormModal(${JSON.stringify(supplier).replace(/"/g, '&quot;')})">
                        <i data-lucide="edit" class="h-4 w-4"></i>
                    </button>
                    <button class="btn-base btn-ghost p-2 h-8 w-8 text-foreground/70" onclick="handleViewCatalog('${supplier.name}')">
                        <i data-lucide="eye" class="h-4 w-4"></i>
                    </button>
                </div>
            </td>
        `;
        supplierTableBody.appendChild(row);
    });

    // Re-initialize lucide icons
    lucide.createIcons();
}

// Initial setup on load
window.onload = function() {
    renderTable();
    lucide.createIcons();
}