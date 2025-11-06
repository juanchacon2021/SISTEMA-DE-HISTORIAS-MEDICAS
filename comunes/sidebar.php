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
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/principal" class="flex items-center"><img class="w-10 h-8 mx-2 my-2 p-2 bg-red-800 rounded-md text-white hover:text-white" src="img/home.svg" alt=""></a>
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/principal" class="text-[18px] ml-3 text-stone-600 font-bold cursor-pointer hover:text-stone-800">CDI - Carmen Estella Mendoza de Flores</a>
                <a href="#" class="ml-8"><img src="img/x.svg" alt="" onclick="Openbar()" style="width: 60px;"></a>
            </div>
            <hr class="my-2 text-gray-600">
            
            <?php
            // Verificar si hay un usuario autenticado
            if (isset($_SESSION['usuario'])) {
                $permisos = $_SESSION['permisos'];
            ?>

            <?php if (isset($_SESSION['usuario'])): ?>
                <script>
                    console.log("Usuario logueado: ID=<?php echo $_SESSION['usuario']; ?>, Nombre=<?php echo $_SESSION['nombre']; ?>");
                </script>
            <?php endif; ?>
                <!-- Inicio siempre visible -->
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/principal" class="block">
                    <div class="iconos p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/home.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg hover:text-white">Inicio</span>
                    </div>
                </a>
                <hr class="my-2 text-gray-600">
                
                <?php if(isset($permisos['modulos']) && in_array('Pacientes', $permisos['modulos'])): ?>
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/pacientes" class="block">
                    <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/usuario.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg hover:text-white">Pacientes</span>
                    </div>
                </a>
                <?php endif; ?>
                
                <?php if(isset($permisos['modulos']) && in_array('Personal', $permisos['modulos'])): ?>
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/personal" class="block">
                    <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/personal.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg hover:text-white">Personal</span>
                    </div>
                </a>
                <?php endif; ?>

                <?php if(isset($permisos['modulos']) && in_array('Usuarios', $permisos['modulos'])): ?>
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/usuarios" class="block">
                    <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/user.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg hover:text-white">Usuarios</span>
                    </div>
                </a>
                <?php endif; ?>
                
                <?php if(isset($permisos['modulos']) && in_array('Examenes', $permisos['modulos'])): ?>
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/examenes" class="block">
                    <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/examen.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg hover:text-white">Examenes</span>
                    </div>
                </a>
                <?php endif; ?>
                
                <?php if(isset($permisos['modulos']) && in_array('Emergencias', $permisos['modulos'])): ?>
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/emergencias" class="block">
                    <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/exclamacion.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg hover:text-white">Emergencias</span>
                    </div>
                </a>
                <?php endif; ?>
                
                <?php if(isset($permisos['modulos']) && in_array('Planificacion', $permisos['modulos'])): ?>
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/planificacion" class="block">
                    <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/pen.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg hover:text-white">Planificación</span>
                    </div>
                </a>
                <?php endif; ?>
                
                <?php if(isset($permisos['modulos']) && in_array('Consultas', $permisos['modulos'])): ?>
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/consultasm" class="block">
                    <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/chart.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg hover:text-white">Consultas</span>
                    </div>
                </a>
                <?php endif; ?>

                <?php if(isset($permisos['modulos']) && in_array('Historias', $permisos['modulos'])): ?>
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/historias" class="block">
                    <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/pdf.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg hover:text-white">Historias Médicas</span>
                    </div>
                </a>
                <?php endif; ?>
                
                <?php if(isset($permisos['modulos']) && in_array('Pasantías', $permisos['modulos'])): ?>
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/pasantias" class="block">
                    <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/student.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg hover:text-white">Pasantías</span>
                    </div>
                </a>
                <?php endif; ?>
                
                <?php if(isset($permisos['modulos']) && in_array('Pacientes crónicos', $permisos['modulos'])): ?>
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/p_cronicos" class="block">
                    <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/cronico.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg hover:text-white">Pacientes Crónicos</span>
                    </div>
                </a>
                <?php endif; ?>
                
                <?php if(isset($permisos['modulos']) && in_array('Jornadas', $permisos['modulos'])): ?>
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/jornadas" class="block">
                    <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/jornadas.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg hover:text-white">Jornadas</span>
                    </div>
                </a>
                <?php endif; ?>
                
                <?php if(isset($permisos['modulos']) && in_array('Inventario', $permisos['modulos'])): ?>
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/inventario" class="block">
                    <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/truck.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg hover:text-white">Inventario</span>
                    </div>
                </a>
                <?php endif; ?>
                
                <?php if(isset($permisos['modulos']) && in_array('Estadistica', $permisos['modulos'])): ?>
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/estadistica" class="block">
                    <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/chart.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg hover:text-white">Estadísticas</span>
                    </div>
                </a>
                <?php endif; ?>
                
                <?php if(isset($permisos['modulos']) && in_array('Bitácora', $permisos['modulos'])): ?>
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/bitacora" class="block">
                    <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/bitacora.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg hover:text-white">Bitácora</span>
                    </div>
                </a>
                <?php endif; ?>

                 <?php if(isset($permisos['modulos']) && in_array('Reportes Parametrizados', $permisos['modulos'])): ?>
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/reportes_p" class="block">
                    <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/pdf2.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg hover:text-white">Reportes</span>
                    </div>
                </a>
                <?php endif; ?>
                
                <!-- Cerrar Sesión -->
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/salida" class="block">
                    <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/bracket.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg">Cerrar Sesión</span>
                    </div>
                </a>
            <?php
            } else {
            ?>
                <!-- Mostrar opción de login si no hay usuario autenticado -->
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/login" class="block">
                    <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/user.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg">Iniciar Sesión</span>
                    </div>
                </a>
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