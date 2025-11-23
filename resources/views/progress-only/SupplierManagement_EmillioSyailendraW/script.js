const initialSuppliers = [
    { id: 1, name: "MediSupply Co.", contactPerson: "David Lee", email: "david@medisupply.com", phone: "+62-555-0101", address: "123 Medical Ave, NY", medicineCount: 145, status: "Active" },
    { id: 2, name: "PharmaCorp Ltd.", contactPerson: "Lisa Chen", email: "lisa@pharmacorp.com", phone: "+62-555-0102", address: "456 Health St, CA", medicineCount: 98, status: "Active" },
    { id: 3, name: "HealthDist Inc.", contactPerson: "James Wilson", email: "james@healthdist.com", phone: "+62-555-0103", address: "789 Wellness Rd, TX", medicineCount: 203, status: "Active" },
    { id: 4, name: "Global Pharma", contactPerson: "Anna Martinez", email: "anna@globalpharma.com", phone: "+62-555-0104", address: "321 Care Blvd, FL", medicineCount: 67, status: "Active" },
    { id: 5, name: "MedSource Direct", contactPerson: "Tom Harris", email: "tom@medsource.com", phone: "+62-555-0105", address: "654 Cure Lane, IL", medicineCount: 42, status: "Inactive" },
];

let suppliers = [...initialSuppliers];
let editingSupplier = null;

const supplierTableBody = document.getElementById('supplier-table-body');
const searchInput = document.getElementById('search-term');
const modal = document.getElementById('form-modal');
const modalTitle = document.getElementById('modal-title');
const formSubmitButton = document.getElementById('form-submit-button');
const supplierForm = document.getElementById('supplier-form');

function getStatusBadge(status) {
    if (status === 'Active') {
        return `<span class="badge-base badge-active">Active</span>`;
    } else {
        return `<span class="badge-base badge-inactive">Inactive</span>`;
    }
}

function openFormModal(supplier) {
    editingSupplier = supplier;
    supplierForm.reset();

    if (supplier) {
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
        modalTitle.textContent = 'Add New Supplier';
        formSubmitButton.textContent = 'Add Supplier';
        document.getElementById('supplier-id').value = '';
        document.getElementById('supplier-status').value = 'Active'; 
    }
    modal.classList.remove('hidden');
}

function closeFormModal(event = null) {
    if (event && event.target.id !== 'form-modal') {
        return; 
    }
    
    if (modal) {
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
        name, contactPerson, email, phone, address, status,
    };

    if (id) {
        suppliers = suppliers.map(supplier => 
            supplier.id === parseInt(id) ? { 
                ...supplier, 
                ...supplierData,
                medicineCount: supplier.medicineCount 
            } : supplier
        );
    } else {
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
}


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
        row.innerHTML = `
            <td class="table-cell cell-style-header">${supplier.name}</td>
            <td class="table-cell text-muted-foreground">${supplier.contactPerson}</td>
            <td class="table-cell text-muted-foreground">${supplier.email}</td>
            <td class="table-cell text-muted-foreground">${supplier.phone}</td>
            <td class="table-cell text-muted-foreground">${supplier.address}</td>
            <td class="table-cell">
                <span class="badge-base badge-catalog">
                    ${supplier.medicineCount} items
                </span>
            </td>
            <td class="table-cell">${getStatusBadge(supplier.status)}</td>
            <td class="table-cell">
                <div class="action-buttons">
                    <button class="btn-base btn-ghost btn-icon btn-icon-primary" onclick="openFormModal(${JSON.stringify(supplier).replace(/"/g, '&quot;')})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                    <button class="btn-base btn-ghost btn-icon btn-icon-muted" onclick="handleViewCatalog('${supplier.name}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
            </td>
        `;
        supplierTableBody.appendChild(row);
    });
}

window.onload = function() {
    renderTable();
}