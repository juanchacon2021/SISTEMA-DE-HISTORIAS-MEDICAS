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
		   //verificamos que exista la variable nivel
		   //que es la que contiene el valor de la sesion

            if (isset($_SESSION['usuario'])) {
                $usuario = $_SESSION['usuario'];
                // Imprime un script de JavaScript para enviar el valor a la consola
                echo "<script>console.log('Usuario actual: " . $usuario . "');</script>";
            } else {
                echo "<script>console.log('no hay ningun usuario');</script>";
            }




            global $nivel;
		    if(!empty($nivel)){
		 ?>
         <?php
					  if($nivel=='Doctor'){
					?>
            <div>
                <div class="iconos p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/usuario.svg" alt="">
                <a href="?pagina=pacientes"><span class="text-[15px] ml-4 text-lg hover:text-white">Pacientes</span></a>
                </div>
                <?php
					  }
					?>

                <hr class="my-2 text-gray-600">
                <?php
					  if($nivel=='Doctor'){
					?>
                    <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:text-white hover:bg-red-800 text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/personal.svg" alt="">
                        <a href="?pagina=personal" ><span class="text-[15px] ml-4 text-lg hover:text-white">Personal</span></a>
                    </div>
                    <?php
					  }
					?>
                    <?php
					  if($nivel=='Doctor' or $nivel=='Enfermera'){
					?>
                    <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:text-white hover:bg-red-800 text-stone-600">
                        <img class="w-6 h-8 imagen" src="img/examen.svg" alt="">
                        <a href="?pagina=examenes" ><span class="text-[15px] ml-4 text-lg hover:text-white">Examenes</span></a>
                    </div>
                    
                </div>

                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:text-white hover:bg-red-800 text-stone-600">

                    <img class="w-6 h-8 imagen" src="img/exclamacion.svg" alt="">
                    <a href="?pagina=emergencias" ><span class="text-[15px] ml-4 text-lg hover:text-white">Emergencia</span></a>
                </div>

                <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:text-white hover:bg-red-800 text-stone-600">

                    <img class="w-6 h-8 imagen" src="img/pen.svg" alt="">
                    <a href="?pagina=planificacion" ><span class="text-[15px] ml-4 text-lg hover:text-white">Planificacion</span></a>
                </div>

                <style>
                    details > div {
                        overflow: hidden;
                        max-height: 0;
                        transition: max-height 0.5s ease;
                    }

                    details[open] > div {
                        max-height: 500px;
                    }
                </style>

                <details class="p-2.5 mt-2 rounded-md px-4 duration-300 cursor-pointer hover:text-white text-stone-600">
                    <summary class="rounded-md duration-300 cursor-pointer flex items-center text-stone-600 hover:bg-stone-600">
                        <img class="w-6 h-8 imagen" src="img/hospital.svg" alt="">
                        <span class="text-[15px] ml-4 text-lg">Centro Médico</span>
                        <span class="ml-8 text-[15px]">&#9660;</span>
                    </summary>
                    <div class="mt-2">
                        <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-10000 cursor-pointer hover:text-white hover:bg-red-800 text-stone-600">
                            <img class="w-6 h-8 imagen" src="img/chart.svg" alt="">
                            <div class="flex justify-between w-full items-center hover:text-white">
                                <a href="?pagina=consultasm"><span class="text-[15px] ml-4 hover:text-white text-lg">Consultas</span></a>
                            </div>
                        </div>
                        <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:text-white hover:bg-red-800 text-stone-600">
                            <img class="w-6 h-8 imagen" src="img/student.svg" alt="">
                            <a href="?pagina=pasantias"><span class="text-[15px] ml-4 text-lg hover:text-white">Pasantías</span></a>
                        </div>
                        <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:text-white hover:bg-red-800 text-stone-600">
                            <img class="w-6 h-8 imagen" src="img/cronico.svg" alt="">
                            <a href="?pagina=p_cronicos"><span class="text-[15px] ml-4 text-lg hover:text-white">Pacientes crónicos</span></a>
                        </div>
                        <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:text-white hover:bg-red-800 text-stone-600">
                            <img class="w-6 h-8 imagen" src="img/jornadas.svg" alt="">
                            <a href="?pagina=jornadas"><span class="text-[15px] ml-4 text-lg hover:text-white">Jornadas</span></a>
                        </div>
                        <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:text-white hover:bg-red-800 text-stone-600">
                            <img class="w-6 h-8 imagen" src="img/truck.svg" alt="">
                            <a href="?pagina=inventario"><span class="text-[15px] ml-4 text-lg hover:text-white">Inventario</span></a>
                        </div>
                    </div>
                </details>
        <?php 		 
				}
		 ?>
           <?php 		 
				}
		 ?>
         <?php
		        
				if(!empty($nivel) and $nivel!=""){
		 ?>
              <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/bracket.svg" alt="">
                    <a href="?pagina=salida" class="text-[15px] ml-4 text-lg">Cerrar Sesión</a>
                </div>
                <?php	
				}
				else{
		 ?>
               
                <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-800 hover:text-white text-stone-600">
                    <img class="w-6 h-8 imagen" src="img/user.svg" alt="">
                    <a href="?pagina=login" class="text-[15px] ml-4 text-lg">Iniciar Sesión</a>
                </div>
                <?php 		 
				}
		 ?>
                </div>
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