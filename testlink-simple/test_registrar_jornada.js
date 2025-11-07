// === DEPENDENCIAS ===
const { Builder, By, until, Key } = require("selenium-webdriver"); 
const xmlrpc = require("xmlrpc");

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL =
  "http://localhost/testlink-code-testlink_1_9_20_fixed/lib/api/xmlrpc/v1/xmlrpc.php";
const DEV_KEY = "0808f3b59861f5c2a52a7d5ca1fab8fa"; // tu API Key
const TEST_CASE_EXTERNAL_ID = "SGM-3"; // cambia al ID real en tu TestLink
const TEST_PLAN_ID = 3; // ✅ tu test plan ID real
const BUILD_NAME = "v.1";

// === FUNCIÓN: Esperar a que un elemento esté presente ===
async function waitForElement(driver, by, timeout = 5000) {
  await driver.wait(until.elementLocated(by), timeout);
  await driver.wait(
    until.elementIsVisible(await driver.findElement(by)),
    timeout
  );
}

// === FUNCIÓN: Seleccionar la primera opción válida de un <select> ===
async function selectFirstOption(driver, selectId) {
  const selectElement = await waitForElement(driver, By.id(selectId));
  
  // Esperar a que al menos una opción real esté presente (excluyendo el placeholder con value="")
  await driver.wait(
    until.elementLocated(By.css(`#${selectId} option:not([value=""])`)), 
    5000, 
    `Timeout esperando opciones en el select #${selectId}`
  );

  // Obtener todas las opciones (incluyendo el placeholder)
  const options = await driver.findElements(By.css(`#${selectId} option`));
  
  if (options.length > 1) {
    // Selecciona la segunda opción (índice 1), asumiendo que el índice 0 es el placeholder
    const optionToSelect = options[1];
    const optionValue = await optionToSelect.getAttribute("value");
    
    // Usamos el método de clic en la opción para asegurar la selección
    await optionToSelect.click();
    console.log(`   > Opción seleccionada para #${selectId} con valor: ${optionValue}`);
    return optionValue;
  } else {
    throw new Error(`No se encontraron opciones válidas para el select #${selectId}.`);
  }
}


// === TEST AUTOMATIZADO: REGISTRAR JORNADA ===
async function runTest() {
  let driver = await new Builder().forBrowser("MicrosoftEdge").build(); 
  let status = "f"; // f = failed | p = passed
  let notes = "";

  try {
    // === Paso 1: Navegar al formulario de login ===
    console.log("🧭 Navegando al formulario de login...");
    await driver.get("http://localhost/SISTEMA-DE-HISTORIAS-MEDICAS/");
    await driver.sleep(2000);

    // === Paso 2: Ingresar credenciales ===
    const captchaElement = await driver.findElement(By.id("captcha-code"));
    const captchaValue = await captchaElement.getText();

    console.log("✏️ Ingresando cédula y contraseña...");
    await waitForElement(driver, By.id("cedula"));
    await driver.findElement(By.id("cedula")).sendKeys("32014004");
    await driver.findElement(By.id("clave")).sendKeys("Dino1234");
    await driver.findElement(By.id("captcha")).sendKeys(captchaValue);
    await driver.findElement(By.id("entrar")).click();
    await driver.sleep(2000);

    // === Paso 3: Ir al módulo de jornadas ===
    console.log('🖱️ Haciendo clic en el enlace "Jornadas"...');
    await waitForElement(
      driver,
      By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/jornadas"]')
    );
    await driver
      .findElement(By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/jornadas"]'))
      .click();
    await driver.sleep(2000);

    // === Paso 4: Abrir el modal de registrar jornada ===
    console.log('🖱️ Haciendo clic en "Nueva Jornada"...');
    await waitForElement(
      driver,
      By.css("button[onclick=\"mostrarModalJornada('incluir')\"]")
    );
    await driver
      .findElement(By.css("button[onclick=\"mostrarModalJornada('incluir')\"]"))
      .click();
    await driver.sleep(1000);

    // === Paso 5: Completar el formulario y seleccionar responsable ===
    console.log("✏️ Llenando el formulario de jornada...");
    await waitForElement(driver, By.id("fecha_jornada"));
    
    // *** CORRECCIÓN DE FECHA ***
    const fechaInput = await driver.findElement(By.id("fecha_jornada"));
    await fechaInput.clear(); // Limpia el campo por seguridad
    await fechaInput.sendKeys("11-06-2025"); 

    await driver.findElement(By.id("ubicacion")).sendKeys("Hospital Central");
    
    // Selección de Responsable (corregida)
    console.log("   > Seleccionando responsable...");
    await selectFirstOption(driver, "cedula_responsable"); 
    
    // Continuar con los campos de número
    await driver.findElement(By.id("pacientes_masculinos")).sendKeys("10");
    await driver.findElement(By.id("pacientes_femeninos")).sendKeys("15");
    await driver.findElement(By.id("pacientes_embarazadas")).sendKeys("5");
    
    // *** CORRECCIÓN TOTAL PACIENTES: Forzar la ejecución de la función JS ***
    // Esto llama a actualizarContadores() de jornadas.js, la cual llena el campo #total_pacientes
    await driver.executeScript('actualizarContadores();');
    
    // === Paso 5.1: Agregar un participante (corregida) ===
    console.log("✏️ Agregando participante...");
    await selectFirstOption(driver, "participante"); // Selecciona el primer participante disponible
    
    // Clic en el botón Agregar
    await driver.findElement(By.css("button[onclick=\"agregarParticipante()\"]")).click();
    
    // Esperar a que la lista se actualice
    await driver.wait(
      until.elementLocated(By.css("#listaParticipantes tr:not(.text-center)")), 
      5000, 
      "Timeout esperando que el participante aparezca en la lista."
    );
    console.log("   > Participante agregado con éxito.");
    await driver.sleep(1000);

    // === Paso 6: Guardar la jornada ===
    console.log('🖱️ Haciendo clic en "Guardar"...');
    await driver.findElement(By.id("btnGuardarJornada")).click();
    await driver.sleep(2000);

    // === Paso 7: Validar que la jornada fue registrada ===
    console.log("⏳ Esperando la aparición del mensaje de éxito...");
    // El modal de éxito tiene el ID 'mostrarmodal'
    await waitForElement(driver, By.id("mostrarmodal"), 5000); 
    const textoExitoElement = await driver.findElement(
      By.id("contenidodemodal")
    );
    const textoObtenido = await textoExitoElement.getText();
    const textoEsperado = "Jornada registrada exitosamente"; // Mensaje de éxito del PHP (modificado)

    if (textoObtenido.trim().includes(textoEsperado)) {
      console.log(
        `✅ Validación exitosa: El modal muestra el texto esperado: "${textoEsperado}"`
      );
      status = "p";
    } else {
      throw new Error(
        `❌ Falló la validación del modal. Esperado: "${textoEsperado}", Obtenido: "${textoObtenido.trim()}"`
      );
    }
  } catch (error) {
    console.error("❌ Error durante la prueba:", error.message);
    notes = "Error: " + error.message;
  } finally {
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

// === FUNCIÓN: Reportar resultado a TestLink ===
async function reportResultToTestLink(status, notes) {
  try {
    const client = xmlrpc.createClient({ url: TESTLINK_URL });

    const params = {
      devKey: DEV_KEY,
      testcaseexternalid: TEST_CASE_EXTERNAL_ID,
      testplanid: TEST_PLAN_ID,
      buildname: BUILD_NAME,
      notes: notes,
      status: status,
    };

    client.methodCall("tl.reportTCResult", [params], function (error, value) {
      if (error) {
        console.error("⚠️ Error al enviar resultado a TestLink:", error);
      } else {
        console.log("📤 Resultado enviado a TestLink:", value);
      }
    });
  } catch (error) {
    console.error("⚠️ No se pudo conectar con TestLink:", error);
  }
}

// === Ejecutar test ===
runTest();