// sample data (mirrors screenshot)
const data = [
  {ts:'2025-10-20 09:45:23', user:'John Smith', role:'Admin', action:'Login', module:'Authentication', details:'Successful login', ip:'192.168.1.100'},
  {ts:'2025-10-20 09:46:15', user:'John Smith', role:'Admin', action:'Create', module:'User Management', details:'Added new user: Emily Brown', ip:'192.168.1.100'},
  {ts:'2025-10-20 10:15:30', user:'Sarah Johnson', role:'Pharmacist', action:'Update', module:'Inventory', details:'Updated stock for Paracetamol 500mg', ip:'192.168.1.105'},
  {ts:'2025-10-20 10:30:45', user:'Mike Davis', role:'Cashier', action:'Create', module:'Transactions', details:'Processed transaction TXN-20251020-001', ip:'192.168.1.110'},
  {ts:'2025-10-20 11:00:12', user:'John Smith', role:'Admin', action:'Update', module:'Supplier Management', details:'Modified supplier: MediSupply Co.', ip:'192.168.1.100'},
  {ts:'2025-10-19 16:20:33', user:'Sarah Johnson', role:'Pharmacist', action:'Create', module:'Purchase Orders', details:'Created PO-2025-005', ip:'192.168.1.105'},
  {ts:'2025-10-19 15:45:18', user:'Mike Davis', role:'Cashier', action:'Login', module:'Authentication', details:'Successful login', ip:'192.168.1.110'},
  {ts:'2025-10-19 14:30:55', user:'Robert Wilson', role:'Cashier', action:'Failed Login', module:'Authentication', details:'Failed login attempt', ip:'192.168.1.115'},
  {ts:'2025-10-19 13:15:42', user:'John Smith', role:'Admin', action:'Delete', module:'User Management', details:'Deactivated user: Test User', ip:'192.168.1.100'},
  {ts:'2025-10-19 12:00:27', user:'Sarah Johnson', role:'Pharmacist', action:'Update', module:'Inventory', details:'Updated expiry date for Amoxicillin 250mg', ip:'192.168.1.105'}
];

const tbody = document.getElementById('tableBody');
const qInput = document.getElementById('q');
const actionFilter = document.getElementById('actionFilter');
const moduleFilter = document.getElementById('moduleFilter');
const exportBtn = document.getElementById('exportBtn');

if (!tbody || !qInput || !actionFilter || !moduleFilter || !exportBtn) {
  console.error('One or more required DOM elements were not found. Check element IDs in index.html.');
}

function badge(action) {
  const key = (action || '').toLowerCase();
  let cls = 'badge';
  if (key === 'login') cls += ' login';
  else if (key === 'create') cls += ' create';
  else if (key === 'update') cls += ' update';
  else if (key.includes('failed')) cls += ' failed';
  else if (key === 'delete') cls += ' delete';
  const safeAction = String(action).replace(/</g, '&lt;').replace(/>/g, '&gt;');
  return `<span class="${cls}">${safeAction}</span>`;
}

function render(list) {
  if (!tbody) return;
  tbody.innerHTML = '';
  if (!list.length) {
    tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:gray;padding:18px">No data found</td></tr>';
    return;
  }
  list.forEach(r => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${escapeHTML(r.ts)}</td>
      <td>${escapeHTML(r.user)}</td>
      <td>${escapeHTML(r.role)}</td>
      <td>${badge(r.action)}</td>
      <td>${escapeHTML(r.module)}</td>
      <td>${escapeHTML(r.details)}</td>
      <td>${escapeHTML(r.ip)}</td>
    `;
    tbody.appendChild(row);
  });
}

function escapeHTML(s) {
  if (s === null || s === undefined) return '';
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function filterData() {
  const act = actionFilter ? actionFilter.value : 'All Actions';
  const mod = moduleFilter ? moduleFilter.value : 'All Modules';
  const q = qInput ? qInput.value.toLowerCase().trim() : '';
  const out = data.filter(d => {
    if (act !== 'All Actions' && d.action !== act) return false;
    if (mod !== 'All Modules' && d.module !== mod) return false;
    if (q) {
      const hay = `${d.ts}|${d.user}|${d.role}|${d.action}|${d.module}|${d.details}|${d.ip}`.toLowerCase();
      if (!hay.includes(q)) return false;
    }
    return true;
  });
  render(out);
}

// CSV helpers with proper escaping for double quotes
function csvEscapeField(val) {
  if (val === null || val === undefined) return '';
  const s = String(val);
  if (s.includes('"') || s.includes(',') || s.includes('\n')) {
    return `"${s.replace(/"/g,'""')}"`;
  }
  return s;
}

function exportCSV(rows) {
  const header = ['Timestamp','User','Role','Action','Module','Details','IP Address'];
  const lines = [header.join(',')];
  rows.forEach(r => {
    const vals = [
      csvEscapeField(r.ts),
      csvEscapeField(r.user),
      csvEscapeField(r.role),
      csvEscapeField(r.action),
      csvEscapeField(r.module),
      csvEscapeField(r.details),
      csvEscapeField(r.ip)
    ];
    lines.push(vals.join(','));
  });
  return lines.join('\n');
}

// events
if (qInput) qInput.addEventListener('input', filterData);
if (actionFilter) actionFilter.addEventListener('change', filterData);
if (moduleFilter) moduleFilter.addEventListener('change', filterData);

if (exportBtn) {
  exportBtn.addEventListener('click', () => {
    const act = actionFilter ? actionFilter.value : 'All Actions';
    const mod = moduleFilter ? moduleFilter.value : 'All Modules';
    const q = qInput ? qInput.value.toLowerCase().trim() : '';
    const out = data.filter(d => {
      if (act !== 'All Actions' && d.action !== act) return false;
      if (mod !== 'All Modules' && d.module !== mod) return false;
      if (q) {
        const hay = `${d.ts}|${d.user}|${d.role}|${d.action}|${d.module}|${d.details}|${d.ip}`.toLowerCase();
        if (!hay.includes(q)) return false;
      }
      return true;
    });

    const csv = exportCSV(out);
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'audit-log.csv';

    document.body.appendChild(a);
    a.click();
    a.remove();

    setTimeout(() => URL.revokeObjectURL(url), 5000);
  });
}

render(data);
