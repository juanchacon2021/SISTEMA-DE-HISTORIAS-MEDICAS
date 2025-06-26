// SIMULACION
document.getElementById('pacientesCount').textContent = '120';
document.getElementById('personalCount').textContent = '15';
document.getElementById('notificacionesCount').textContent = (localStorage.getItem('notificacionesGuardadas') ? JSON.parse(localStorage.getItem('notificacionesGuardadas')).length : 0);

// Calendario simple
function renderCalendar() {
    const calendar = document.getElementById('calendar');
    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDay = new Date(year, month, 1).getDay();
    const monthNames = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

    let html = `<div style="text-align:center;
                font-weight:600;
                margin-bottom:8px;">${monthNames[month]} ${year}</div>`;
    
                html += '<table style="width:100%; border-collapse:collapse; text-align:center; font-size:15px;"><tr>';
    ['D','L','M','M','J','V','S'].forEach(d => html += `<th style="color:rgb(220,38,38);padding:3px 0;">${d}</th>`);
    html += '</tr><tr>';

    let day = 1;
    for(let i=0; i<42; i++) {
        if(i < firstDay || day > daysInMonth) {
            html += '<td style="padding:5px 0;"></td>';
        } else {
            let style = (day === now.getDate()) ? 'background:rgb(220,38,38);color:#fff;border-radius:50%;' : '';
            html += `<td style="padding:5px 0;${style}">${day}</td>`;
            day++;
        }
        if((i+1)%7 === 0 && day <= daysInMonth) html += '</tr><tr>';
    }
    html += '</tr></table>';
    calendar.innerHTML = html;
}
renderCalendar();

function toggleUserDropdown(e) {
    e.stopPropagation();
    document.getElementById('userDropdownMenu').classList.toggle('show');
}
document.getElementById('userDropdownBtn').onclick = toggleUserDropdown;
document.querySelector('.user-name').onclick = toggleUserDropdown;
// Cerrar el dropdown al hacer click fuera
document.addEventListener('click', function(e) {
    var menu = document.getElementById('userDropdownMenu');
    if(menu.classList.contains('show')) menu.classList.remove('show');
});