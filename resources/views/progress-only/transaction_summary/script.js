const form = document.getElementById("transactionForm");
const tableBody = document.querySelector("#transactionTable tbody");
const totalTransactionsEl = document.getElementById("totalTransactions");
const totalRevenueEl = document.getElementById("totalRevenue");


let transactions = [];

function generateTransactionId() {
  const randomInt = Math.floor(Math.random() * 90000) + 10000;
  const randomStr = Math.random().toString(36).substring(2, 5).toUpperCase();
  return `TX-${randomStr}${randomInt}`;
}

function getCurrentDateTime() {
  const now = new Date();
  const date = now.toISOString().split("T")[0];
  const time = now.toTimeString().split(" ")[0].slice(0, 5);
  return { date, time };
}

document.addEventListener("DOMContentLoaded", () => {
  const navLinks = document.querySelectorAll(".nav-link");
  const currentPath = window.location.pathname;

  navLinks.forEach(link => {
    const href = link.getAttribute("href");

    if (currentPath.includes(href.replace("../", ""))) {
      link.classList.add("active");
    }
  });
});

function renderTable() {
  tableBody.innerHTML = "";
  transactions.forEach((t, index) => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${t.id}</td>
      <td>${t.date}</td>
      <td>${t.time}</td>
      <td>${t.cashier}</td>
      <td><span class="badge ${t.type}">${t.type}</span></td>
      <td>${t.items}</td>
      <td>Rp ${t.amount.toLocaleString()}</td>
      <td>${t.payment}</td>
      <td>
        <button class="action-btn edit" onclick="editTransaction(${index})">Edit</button>
        <button class="action-btn delete" onclick="deleteTransaction(${index})">Delete</button>
      </td>
    `;
    tableBody.appendChild(row);
  });

  updateSummary();
}

function updateSummary() {
  const totalTransactions = transactions.length;
  const totalRevenue = transactions.reduce((sum, t) => sum + t.amount, 0);
  totalTransactionsEl.textContent = totalTransactions;
  totalRevenueEl.textContent = `Rp ${totalRevenue.toLocaleString()}`;
}

form.addEventListener("submit", (e) => {
  e.preventDefault();

  const { date, time } = getCurrentDateTime();
  const editIndex = form.dataset.editIndex;

  const transactionData = {
    id: editIndex !== undefined ? transactions[editIndex].id : generateTransactionId(),
    date,
    time,
    cashier: document.getElementById("cashierName").value.trim(),
    type: document.getElementById("type").value,
    items: parseInt(document.getElementById("items").value),
    amount: parseInt(document.getElementById("amount").value),
    payment: document.getElementById("payment").value
  };

  if (editIndex !== undefined) {
    transactions[editIndex] = transactionData;
    delete form.dataset.editIndex; 
  } else {
    // Tambah transaksi
    transactions.push(transactionData);
  }

  renderTable();
  form.reset();
});


function editTransaction(index) {
  const t = transactions[index];
  document.getElementById("cashierName").value = t.cashier;
  document.getElementById("items").value = t.items;
  document.getElementById("amount").value = t.amount;
  document.getElementById("type").value = t.type;
  document.getElementById("payment").value = t.payment;

  form.dataset.editIndex = index; 
}


function deleteTransaction(index) {
  if (confirm("Delete this transaction?")) {
    transactions.splice(index, 1);
    renderTable();
  }
}

renderTable();
