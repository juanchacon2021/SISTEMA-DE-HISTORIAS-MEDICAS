// === DEPENDENCIAS ===
const { Builder, By, until, Key } = require("selenium-webdriver");
const xmlrpc = require("xmlrpc");

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL =
  "http://localhost/testlink-code-testlink_1_9_20_fixed/lib/api/xmlrpc/v1/xmlrpc.php";
const DEV_KEY = "0808f3b59861f5c2a52a7d5ca1fab8fa"; // tu API Key
const TEST_CASE_EXTERNAL_ID = "SGM-9"; // ✅ ID para Incluir Estudiante
const TEST_PLAN_ID = 3; // ✅ tu test plan ID real
const BUILD_NAME = "v.1";

// === FUNCIÓN: Esperar a que un elemento esté presente y DEVOLVERLO ===
async function waitForElement(driver, by, timeout = 5000) {
  // 1. Esperar a que el elemento esté en el DOM y obtenerlo
  const element = await driver.wait(until.elementLocated(by), timeout);
  // 2. Esperar a que el elemento sea visible
  await driver.wait(until.elementIsVisible(element), timeout);
  // 3. Devolver el elemento para que pueda usarse
  return element;
}

// === FUNCIÓN: Seleccionar la primera opción válida de un <select> ===
async function selectFirstOption(driver, selectId) {
  const selectElement = await waitForElement(driver, By.id(selectId)); // Esperar a que al menos una opción real esté presente
  await driver.wait(
    until.elementLocated(By.css(`#${selectId} option:not([value=""])`)),
    5000,
    `Timeout esperando opciones en el select #${selectId}`
  );

  const options = await driver.findElements(By.css(`#${selectId} option`));
  if (options.length > 1) {
    const optionToSelect = options[1];
    const optionValue = await optionToSelect.getAttribute("value");
    await optionToSelect.click();
    console.log(
      `   > Opción seleccionada para #${selectId} con valor: ${optionValue}`
    );
    return optionValue;
  } else {
    throw new Error(
      `No se encontraron opciones válidas para el select #${selectId}.`
    );
  }
}

// === TEST AUTOMATIZADO: INCLUIR ESTUDIANTE (SGM-9) ===
async function runTest() {
  let driver = await new Builder().forBrowser("MicrosoftEdge").build();
  let status = "f"; // f = failed | p = passed
  let notes = "";

  try {
    // === Paso 1: Navegar y Login ===
    console.log("🧭 Navegando al formulario de login...");
    await driver.get("http://localhost/SISTEMA-DE-HISTORIAS-MEDICAS/");
    await driver.sleep(2000);

    const captchaElement = await driver.findElement(By.id("captcha-code"));
    const captchaValue = await captchaElement.getText();

    console.log("✏️ Ingresando cédula y contraseña...");
    await waitForElement(driver, By.id("cedula"));
    await driver.findElement(By.id("cedula")).sendKeys("32014004");
    await driver.findElement(By.id("clave")).sendKeys("Dino1234");
    await driver.findElement(By.id("captcha")).sendKeys(captchaValue);
    await driver.findElement(By.id("entrar")).click();
    await driver.sleep(2000); // === Paso 2: Ir al módulo de Pasantías ===

    console.log('🖱️ Haciendo clic en el enlace "Pasantías"...');
    await waitForElement(
      driver,
      By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/pasantias"]')
    );
    await driver
      .findElement(By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/pasantias"]'))
      .click();
    await driver.sleep(2000);

    // === Paso 3: Abrir el modal de registro de Estudiante ===
    console.log('🖱️ Haciendo clic en el botón "Nuevo Estudiante"...');
    const btnNuevoEstudiante = await waitForElement(
      driver,
      By.css("button[onclick=\"mostrarModalEstudiante('incluir')\"]")
    );
    await btnNuevoEstudiante.click();
    await driver.sleep(1000);

    // === Paso 4: Llenar el formulario de estudiante (CORRECCIÓN APLICADA) ===
    console.log("✏️ Llenando el formulario de estudiante...");

    // Generar una cédula única para evitar duplicados
    console.log("✏️ Llenando el formulario de estudiante...");

    // Generar una cédula única de 8 cifras (entre 10,000,000 y 99,999,999)
    const minCedula = 10000000;
    const maxCedula = 99999999;
    const cedulaUnica =
      Math.floor(Math.random() * (maxCedula - minCedula + 1)) + minCedula;
    console.log(` > Cédula generada: ${cedulaUnica}`); // Opcional: para ver qué cédula se usa

    // Esperar y obtener CÉDULA
    const cedulaInput = await waitForElement(
      driver,
      By.id("cedula_estudiante")
    );
    await cedulaInput.sendKeys(String(cedulaUnica));

    const nombreInput = await waitForElement(driver, By.id("nombre"));
    await nombreInput.sendKeys("Pedro");

    // Esperar y obtener APELLIDO
    const apellidoInput = await waitForElement(driver, By.id("apellido"));
    await apellidoInput.sendKeys("Gómez");

    const institucionInput = await waitForElement(driver, By.id("institucion"));
    await institucionInput.sendKeys("UPTAEB");

    // Esperar y obtener TELÉFONO
    const telefonoInput = await waitForElement(driver, By.id("telefono"));
    await telefonoInput.sendKeys("04123334455");

    // Seleccionar la primera área disponible
    console.log("  > Seleccionando el Área de Pasantía...");
    await selectFirstOption(driver, "cod_area");
    await driver.sleep(500);

    // === Paso 5: Guardar el estudiante ===
    console.log('🖱️ Haciendo clic en "Guardar Estudiante"...');
    // Esperamos el botón por si el modal tarda en cargarlo
    const btnGuardar = await waitForElement(
      driver,
      By.id("btnGuardarEstudiante")
    );
    await btnGuardar.click();
    await driver.sleep(2000); // === Paso 6: Validar que el estudiante fue registrado ===

    console.log("⏳ Esperando la aparición del mensaje de éxito...");
    // El mensaje de éxito general aparece en el modal 'mostrarmodal'
    await waitForElement(driver, By.id("mostrarmodal"), 5000);
    const textoExitoElement = await driver.findElement(
      By.id("contenidodemodal")
    );
    const textoObtenido = await textoExitoElement.getText(); // Mensaje de éxito esperado
    const textoEsperado = "Estudiante registrado exitosamente";

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
