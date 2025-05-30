<header class="sidebarr">
    <span class="absolute text-white text-2xl top-4 left-4 cursor-pointer" onclick="Openbar()">
        <img class="w-12 h-10 mx-2 my-2 p-2 bg-red-800 rounded-md text-white hover:text-white" src="img/bars-solid.svg" alt="">
    </span>
    <div class="sidebar fixed top-0 bottom-0 lg:left-0 left-[-300px] duration-1000 
        p-2 w-[300px] overflow-y-auto text-center bg-white shadow h-screen">
        <div class="text-stone-600 text-xl">
            <div class="p-2.5 mt-1 flex items-center rounded-md ">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-sky-500"></span>
                </span>
                <a href="?pagina=principal"><img class="w-10 h-8 mx-2 my-2 p-2 bg-red-800 rounded-md text-white hover:text-white" src="img/home.svg" alt=""></a>
                <a href="?pagina=principal" class="text-[18px] ml-3 text-stone-600 font-bold cursor-pointer hover:text-stone-800">CDI - Carmen Estella Mendoza de Flores</a>
                <a href="#" class="ml-8"><img src="img/x.svg" alt="" onclick="Openbar()" style="width: 60px;"></a>
            </div>
            <hr class="my-2 text-gray-600">
            
            <?php
            // Verificar si hay un usuario autenticado
            if (isset($_SESSION['usuario'])) {
                $permisos = $_SESSION['permisos'];
            ?>
                <!-- Inicio siempre visible -->
                <div class="iconos p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/home.svg" alt="">
                    <a href="?pagina=principal"><span class="text-[15px] ml-4 text-lg hover:text-white">Inicio</span></a>
                </div>
                <hr class="my-2 text-gray-600">
                
                <?php if(in_array('Pacientes', $permisos)): ?>
                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/usuario.svg" alt="">
                    <a href="?pagina=pacientes"><span class="text-[15px] ml-4 text-lg hover:text-white">Pacientes</span></a>
                </div>
                <?php endif; ?>
                
                <?php if(in_array('Personal', $permisos)): ?>
                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/personal.svg" alt="">
                    <a href="?pagina=personal"><span class="text-[15px] ml-4 text-lg hover:text-white">Personal</span></a>
                </div>
                <?php endif; ?>

                <?php if(in_array('Usuarios', $permisos)): ?>
                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/user.svg" alt="">
                    <a href="?pagina=usuarios"><span class="text-[15px] ml-4 text-lg hover:text-white">Usuarios</span></a>
                </div>
                <?php endif; ?>
                
                <?php if(in_array('Examenes', $permisos)): ?>
                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/examen.svg" alt="">
                    <a href="?pagina=examenes"><span class="text-[15px] ml-4 text-lg hover:text-white">Examenes</span></a>
                </div>
                <?php endif; ?>
                
                <?php if(in_array('Emergencias', $permisos)): ?>
                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/exclamacion.svg" alt="">
                    <a href="?pagina=emergencias"><span class="text-[15px] ml-4 text-lg hover:text-white">Emergencias</span></a>
                </div>
                <?php endif; ?>
                
                <?php if(in_array('Planificacion', $permisos)): ?>
                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/pen.svg" alt="">
                    <a href="?pagina=planificacion"><span class="text-[15px] ml-4 text-lg hover:text-white">Planificación</span></a>
                </div>
                <?php endif; ?>
                
                <?php if(in_array('Consultas', $permisos)): ?>
                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/chart.svg" alt="">
                    <a href="?pagina=consultasm"><span class="text-[15px] ml-4 text-lg hover:text-white">Consultas</span></a>
                </div>
                <?php endif; ?>
                
                <?php if(in_array('Pasantías', $permisos)): ?>
                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/student.svg" alt="">
                    <a href="?pagina=pasantias"><span class="text-[15px] ml-4 text-lg hover:text-white">Pasantías</span></a>
                </div>
                <?php endif; ?>
                
                <?php if(in_array('Pacientes crónicos', $permisos)): ?>
                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/cronico.svg" alt="">
                    <a href="?pagina=p_cronicos"><span class="text-[15px] ml-4 text-lg hover:text-white">Pacientes Crónicos</span></a>
                </div>
                <?php endif; ?>
                
                <?php if(in_array('Jornadas', $permisos)): ?>
                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/jornadas.svg" alt="">
                    <a href="?pagina=jornadas"><span class="text-[15px] ml-4 text-lg hover:text-white">Jornadas</span></a>
                </div>
                <?php endif; ?>
                
                <?php if(in_array('Inventario', $permisos)): ?>
                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/truck.svg" alt="">
                    <a href="?pagina=inventario"><span class="text-[15px] ml-4 text-lg hover:text-white">Inventario</span></a>
                </div>
                <?php endif; ?>
                <?php if(in_array('Estadistica', $permisos)): ?>
                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/chart.svg" alt="">
                    <a href="?pagina=estadistica"><span class="text-[15px] ml-4 text-lg hover:text-white">Estadísticas</span></a>
                </div>
                <?php endif; ?>
                 <?php if(in_array('Bitácora', $permisos)): ?>
                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/bitacora.svg" alt="">
                    <a href="?pagina=bitacora"><span class="text-[15px] ml-4 text-lg hover:text-white">Bitácora</span></a>
                </div>
                <?php endif; ?>
                <!-- Cerrar Sesión -->
                <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/bracket.svg" alt="">
                    <a href="?pagina=salida" class="text-[15px] ml-4 text-lg">Cerrar Sesión</a>
                </div>
            <?php
            } else {
            ?>
                <!-- Mostrar opción de login si no hay usuario autenticado -->
                <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/user.svg" alt="">
                    <a href="?pagina=login" class="text-[15px] ml-4 text-lg">Iniciar Sesión</a>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    
    <script src="js/sidebar.js"></script>
</header>

<script>
function toggleAccordion(id) {
    const menu = document.getElementById(id);
    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
    } else {
        menu.classList.add('hidden');
    }
}
</script>