// Navegación muy básica
function mostrarPagina(id){
  document.querySelectorAll('.pagina').forEach(p=>p.classList.remove('activa'));
  document.getElementById(id).classList.add('activa');
  window.scrollTo(0,0);
} 

async function iniciarSesion(){
  const usuario = document.getElementById('usuario').value.trim();
  const contrasena = document.getElementById('contrasena').value;

  try{
    const resp = await fetch('api/login.php', {
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body: JSON.stringify({usuario, contrasena})
    });

    // si el servidor respondió con error 500/404, mostramos el cuerpo como texto
    if(!resp.ok){
      const txt = await resp.text();
      alert('Error del servidor ('+resp.status+'): ' + txt);
      return;
    }

    const data = await resp.json();
    if(data.success){
      localStorage.setItem('user_id', data.user_id);
      localStorage.setItem('es_admin', data.es_admin ? '1' : '0');
      document.getElementById('nombre-usuario').textContent = usuario;
      mostrarPagina('pagina-panel');
      if(data.es_admin){ document.getElementById('admin-panel').style.display='block'; }
      else{ document.getElementById('admin-panel').style.display='none'; }
    }else{
      alert('Error: ' + (data.message || 'Credenciales inválidas'));
    }
  }catch(e){
    alert('Error de red: ' + e.message);
  }
}


function cerrarSesion(){
  localStorage.removeItem('user_id');
  localStorage.removeItem('es_admin');
  mostrarPagina('pagina-inicio');
  document.getElementById('admin-panel').style.display='none';
}

// Registro muy simple
document.addEventListener('DOMContentLoaded', ()=>{
  const form = document.getElementById('form-registro');
  form.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const fd = new FormData(form);
    const r = await fetch('api/register.php', {method:'POST', body:fd});
    const j = await r.json();
    if(j.success){ alert('Cuenta creada. Ahora ingresa.'); form.reset(); form.style.display='none'; }
    else alert(j.message || 'No se pudo registrar');
  });
});

// Carga de usuarios (solo admin)
async function cargarUsuarios(){
  try{
    const r = await fetch('api/admin_users.php');
    const j = await r.json();
    const wrap = document.getElementById('tabla-usuarios');
    if(!j.success){ wrap.innerHTML = '<p>Error.</p>'; return; }
    let html = '<table class="tabla"><thead><tr><th>ID</th><th>Usuario</th><th>Email</th><th>Admin</th><th>Creado</th></tr></thead><tbody>';
    j.data.forEach(u=>{
      html += `<tr><td>${u.id}</td><td>${u.username}</td><td>${u.email}</td><td>${u.is_admin?'Sí':'No'}</td><td>${u.created_at}</td></tr>`;
    });
    html += '</tbody></table>';
    wrap.innerHTML = html;
  }catch(e){ console.error(e); }
}
