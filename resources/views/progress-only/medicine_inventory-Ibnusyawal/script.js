// Initial medicines data
let medicines = [
  {
    id: 1,
    name: "Paracetamol 500mg",
    code: "MED001",
    category: "Pain Relief",
    stock: 150,
    expiryDate: "2025-11-15",
  },
  {
    id: 2,
    name: "Amoxicillin 250mg",
    code: "MED002",
    category: "Antibiotic",
    stock: 80,
    expiryDate: "2025-11-20",
  },
  {
    id: 3,
    name: "Ibuprofen 400mg",
    code: "MED003",
    category: "Pain Relief",
    stock: 200,
    expiryDate: "2025-12-05",
  },
  {
    id: 4,
    name: "Ciprofloxacin 500mg",
    code: "MED004",
    category: "Antibiotic",
    stock: 45,
    expiryDate: "2025-12-10",
  },
  {
    id: 5,
    name: "Omeprazole 20mg",
    code: "MED005",
    category: "Antacid",
    stock: 120,
    expiryDate: "2026-01-15",
  },
  {
    id: 6,
    name: "Metformin 500mg",
    code: "MED006",
    category: "Diabetes",
    stock: 95,
    expiryDate: "2026-02-20",
  },
  {
    id: 7,
    name: "Aspirin 75mg",
    code: "MED007",
    category: "Blood Thinner",
    stock: 180,
    expiryDate: "2026-03-10",
  },
  {
    id: 8,
    name: "Loratadine 10mg",
    code: "MED008",
    category: "Antihistamine",
    stock: 60,
    expiryDate: "2025-12-25",
  },
  {
    id: 9,
    name: "Lisinopril 10mg",
    code: "MED009",
    category: "Blood Pressure",
    stock: 110,
    expiryDate: "2026-01-30",
  },
  {
    id: 10,
    name: "Atorvastatin 20mg",
    code: "MED010",
    category: "Cholesterol",
    stock: 15,
    expiryDate: "2025-11-28",
  },
];

let editingMedicineId = null;

// Sidebar toggle functionality
const sidebar = document.getElementById("sidebar");
const mobileMenuBtn = document.getElementById("mobileMenuBtn");
const closeSidebarBtn = document.getElementById("closeSidebarBtn");

function toggleSidebar() {
  sidebar.classList.toggle("open");
}

mobileMenuBtn.addEventListener("click", toggleSidebar);
closeSidebarBtn.addEventListener("click", toggleSidebar);

// Close sidebar when clicking outside on mobile
document.addEventListener("click", (e) => {
  if (window.innerWidth <= 768) {
    if (!sidebar.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
      sidebar.classList.remove("open");
    }
  }
});

// Get stock status badge HTML
function getStockStatus(stock) {
  if (stock <= 20) {
    return '<span class="badge badge-destructive">Low Stock</span>';
  } else if (stock <= 50) {
    return '<span class="badge badge-outline badge-orange">Medium</span>';
  }
  return '<span class="badge badge-outline badge-green">In Stock</span>';
}

// Render medicines table
function renderMedicines(medicinesToRender = medicines) {
  const tbody = document.getElementById("medicineTableBody");
  tbody.innerHTML = "";

  medicinesToRender.forEach((medicine) => {
    const row = document.createElement("tr");
    row.innerHTML = `
                    <td>${medicine.name}</td>
                    <td>${medicine.code}</td>
                    <td>${medicine.category}</td>
                    <td>${medicine.stock}</td>
                    <td>${medicine.expiryDate}</td>
                    <td>${getStockStatus(medicine.stock)}</td>
                    <td>
                        <button class="btn btn-ghost btn-sm edit-btn" data-id="${
                          medicine.id
                        }">Edit</button>
                    </td>
                `;
    tbody.appendChild(row);
  });

  // Add event listeners to edit buttons
  document.querySelectorAll(".edit-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = parseInt(btn.getAttribute("data-id"));
      openEditModal(id);
    });
  });
}

// Search functionality
document.getElementById("searchInput").addEventListener("input", (e) => {
  const searchTerm = e.target.value.toLowerCase();
  const filtered = medicines.filter(
    (medicine) =>
      medicine.name.toLowerCase().includes(searchTerm) ||
      medicine.code.toLowerCase().includes(searchTerm) ||
      medicine.category.toLowerCase().includes(searchTerm)
  );
  renderMedicines(filtered);
});

// Modal functions
function openModal() {
  document.getElementById("medicineModal").classList.add("open");
}

function closeModal() {
  document.getElementById("medicineModal").classList.remove("open");
  document.getElementById("medicineForm").reset();
  editingMedicineId = null;
  document.getElementById("modalTitle").textContent = "Add New Medicine";
  document.getElementById("modalDescription").textContent =
    "Enter the medicine details to add to inventory.";
  document.getElementById("submitBtn").textContent = "Add Medicine";
}

function openEditModal(id) {
  const medicine = medicines.find((m) => m.id === id);
  if (!medicine) return;

  editingMedicineId = id;
  document.getElementById("medicineName").value = medicine.name;
  document.getElementById("medicineCode").value = medicine.code;
  document.getElementById("medicineCategory").value = medicine.category;
  document.getElementById("medicineQuantity").value = medicine.stock;
  document.getElementById("medicineExpiry").value = medicine.expiryDate;

  document.getElementById("modalTitle").textContent = "Edit Medicine";
  document.getElementById("modalDescription").textContent =
    "Update the medicine details below.";
  document.getElementById("submitBtn").textContent = "Update Medicine";

  openModal();
}

// Add medicine button
document.getElementById("addMedicineBtn").addEventListener("click", openModal);

// Cancel button
document.getElementById("cancelBtn").addEventListener("click", closeModal);

// Close modal on overlay click
document.getElementById("medicineModal").addEventListener("click", (e) => {
  if (e.target.id === "medicineModal") {
    closeModal();
  }
});

// Form submission
document.getElementById("medicineForm").addEventListener("submit", (e) => {
  e.preventDefault();

  const formData = {
    name: document.getElementById("medicineName").value,
    code: document.getElementById("medicineCode").value,
    category: document.getElementById("medicineCategory").value,
    stock: parseInt(document.getElementById("medicineQuantity").value),
    expiryDate: document.getElementById("medicineExpiry").value,
  };

  if (editingMedicineId) {
    // Update existing medicine
    const index = medicines.findIndex((m) => m.id === editingMedicineId);
    if (index !== -1) {
      medicines[index] = { ...medicines[index], ...formData };
    }
  } else {
    // Add new medicine
    const newMedicine = {
      id:
        medicines.length > 0 ? Math.max(...medicines.map((m) => m.id)) + 1 : 1,
      ...formData,
    };
    medicines.push(newMedicine);
  }

  renderMedicines();
  closeModal();
});

// Initial render
renderMedicines();
