<header class="sidebarr">
        <span class="absolute text-white text-2xl top-7 left-4 cursor-pointer" onclick="Openbar()">
            <i class="fa-solid fa-bars py-2 px-2 bg-red-600 rounded-md" style="color: #ffffff;"></i>
        </span>
        <div class="sidebar fixed top-0 bottom-0 lg:left-0 left-[-300px] duration-1000 
            p-2 w-[300px] overflow-y-auto text-center bg-white shadow h-screen">
            <div class="text-stone-600 text-xl">
            <div class="p-2.5 mt-1 flex items-center rounded-md ">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-sky-500"></span>
                </span>
                <a href="?pagina=principal"><i class="fa-solid fa-house px-2 py-2 bg-red-800 rounded-md text-white hover:text-white"></i></a>
                <a href="?pagina=principal" class="text-[18px] ml-3 text-stone-600 font-bold cursor-pointer text-stone-500 hover:text-stone-800">CDI - Carmen Estella Mendoza de Flores</a>
                <a href="#" class="ml-8"><i class="bi bi-x cursor-pointer lg:hidden cursor-pointer hover:text-white" onclick="Openbar()"></i></a>
            </div>
            <hr class="my-2 text-gray-600">

            <div>
                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <i class="fa-solid fa-hospital-user"></i>
                <a href="?pagina=pacientes"><span class="text-[15px] ml-4 text-lg hover:text-white">Pacientes</span></a>
                </div>

                <hr class="my-4 text-gray-600">

                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white">
                    <i class="fa-solid fa-hospital"></i>
                <div class="flex justify-between w-full items-center hover:text-white text-stone-600" onclick="dropDown1()">
                    <span class="text-[15px] ml-4 text-lg">Centro Medico</span>
                    <span class="text-sm rotate-180" id="arrow1">
                        <i class="fa-solid fa-angle-down"></i>
                    </span>
                </div>
                </div>
                <div class=" leading-7 text-left text-sm font-thin mt-2 w-4/5 mx-auto" id="submenu">

                    <!--    PERSONAL: Registra a un nuevo usuario otorgandole los permisos que el usuario mayor (Doctor) quiera que ese usuario tenga-->
                    <a href="?pagina=personal"><h1 class="cursor-pointer p-2 hover:bg-red-800 rounded-md mt-1 text-lg hover:text-white">Personal</h1></a>

                    <!--    PERMISOS: Muestra la lista de permisos que tiene el usuario ingresado ejemplo
                    Al doctor le deben aparecer todos los permisos en cambio a los usuarios que creo el doctor solo le deben
                    aparecer los permisos que le otorgaron
                    <a href=""><h1 class="cursor-pointer p-2 hover:bg-red-800 rounded-md mt-1 text-lg hover:text-white">Permisos</h1></a>
                -->
                    <a href="?pagina=examenes"><h1 class="cursor-pointer p-2 hover:bg-red-800 rounded-md mt-1 text-lg hover:text-white">Examenes</h1></a>
                </div>

                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:text-white hover:bg-red-800 text-stone-600">
                    <!--    ATENCION este espacio lleva a una linea de atencion donde el usuario coloca la cedula del Paciente
                    y el aparece todo lo relacionado con ese paciente de forma basica, etc
                    tambien debe tener un boton de agregar paciente...-->
                    <i class="fa-solid fa-circle-exclamation hover:text-white"></i>  
                    <a href="?pagina=emergencias" ><span class="text-[15px] ml-4 text-lg hover:text-white">Emergencia</span></a>
                </div>

                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-10000 cursor-pointer hover:text-white hover:bg-red-800 text-stone-600">
                    <i class="fa-solid fa-chart-simple hover:text-white"></i>
                    <div class="flex justify-between w-full items-center hover:text-white">
                        <a href="?pagina=consultasm"><span class="text-[15px] ml-4 hover:text-white text-lg">Consultas</span></a>
                        
                    </div>
                </div>
                <div class=" leading-7 text-left text-sm font-thin mt-2 w-4/5 mx-auto" id="submenu2">

                    <!--    USUARIOS: Muestra la cantidad de usuarios registrados en el sistema (esta opcion solo debe ser visible para el doctor)-->
                    <a href=""><h1 class="cursor-pointer p-2 hover:bg-red-800 hover:text-white rounded-md mt-1 text-lg">Usuarios</h1></a>

                    <!--    Historias: Muestra absolutamente toda la historia medica de un paciente en especial
                            Opino que deberia tener tambien un boton de imprimir para que se descargue en PDF
                            toda su historia medica
                    -->
                    <a href="?pagina=historias"><h1 class="cursor-pointer p-2 hover:bg-red-800 hover:text-white rounded-md mt-1 text-lg">Historias</h1></a>
                </div>

                <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="text-[15px] ml-4 text-lg">Cerrar Sesión</span>
                </div>

                </div>
            </div>
        </div>
    
    <script src="js/sidebar.js"></script>
    </header>