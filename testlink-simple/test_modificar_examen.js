// === DEPENDENCIAS ===
const { Builder, By, until, Key } = require("selenium-webdriver");
const xmlrpc = require("xmlrpc");

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL =
  "http://localhost/testlink-code-testlink_1_9_20_fixed/lib/api/xmlrpc/v1/xmlrpc.php";
const DEV_KEY = "0808f3b59861f5c2a52a7d5ca1fab8fa"; // tu API Key
const TEST_CASE_EXTERNAL_ID = "SGM-23"; // ✅ ID para Modificación de Registro de Examen
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

// === FUNCIÓN: Generar Hora Modificada en Formato Válido ===

// === TEST AUTOMATIZADO: MODIFICAR REGISTRO DE EXAMEN (SGM-23) ===
async function runTest() {
  let driver = await new Builder().forBrowser("MicrosoftEdge").build();
  let status = "f"; // f = failed | p = passed
  let notes = "";
  const nuevaHora = "0130p";

  try {
    // === Paso 1 y 2: Login y Navegar a Exámenes ===
    console.log("🧭 Navegando al formulario de login...");
    await driver.get("http://localhost/SISTEMA-DE-HISTORIAS-MEDICAS/");
    await driver.sleep(2000);

    const captchaElement = await driver.findElement(By.id("captcha-code"));
    const captchaValue = await captchaElement.getText();

    console.log("✏️ Ingresando credenciales...");
    await waitForElement(driver, By.id("cedula"));
    await driver.findElement(By.id("cedula")).sendKeys("32014004");
    await driver.findElement(By.id("clave")).sendKeys("Dino1234");
    await driver.findElement(By.id("captcha")).sendKeys(captchaValue);
    await driver.findElement(By.id("entrar")).click();
    await driver.sleep(2000); // Navegar al módulo de Exámenes

    console.log('🖱️ Haciendo clic en el enlace "Exámenes Médicos"...');
    await waitForElement(
      driver,
      By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/examenes"]')
    );
    await driver
      .findElement(By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/examenes"]'))
      .click();
    await driver.sleep(2000);

    // === Paso 3: Abrir modal de Modificación ===
    console.log(
      '🖱️ Buscando y haciendo clic en el botón "Modificar" del primer registro...'
    );

    // Asumimos que el botón de modificar está en la tabla de registros y llama a la función JS 'editarRegistroExamen'
    const btnModificar = await waitForElement(
      driver,
      By.css("button[onclick*='editarRegistroExamen']"),
      10000 // Esperar más tiempo por la carga de la tabla
    );
    await btnModificar.click();
    await driver.sleep(1500);

    console.log(" > Seleccionando el primer Paciente disponible...");
    const selectPaciente = await waitForElement(
      driver,
      By.id("pacienteExamen")
    );
    // Seleccionar la segunda opción (índice 1, asumiendo que la primera es un placeholder/vacío)
    await driver
      .findElement(By.css("#pacienteExamen option:nth-child(2)"))
      .click();
    await driver.sleep(500);

    // Campo Tipo de Examen (Select: #cod_examen)
    console.log(" > Seleccionando el primer Tipo de Examen disponible...");
    const selectTipoExamen = await waitForElement(driver, By.id("tipoExamen"));
    // Seleccionar la segunda opción
    await driver.findElement(By.css("#tipoExamen option:nth-child(2)")).click();
    await driver.sleep(500);

    // === Paso 4: Modificar campos del Registro ===
    console.log("✏️ Modificando la observación y la hora...");

    // Campo Observación (Textarea: #observacionExamen)
    const observacionInput = await waitForElement(
      driver,
      By.id("observacionExamen")
    );
    // Limpiar y enviar nueva observación
    await observacionInput.sendKeys(Key.CONTROL, "a");
    await observacionInput.sendKeys(Key.DELETE);
    const nuevaObservacion =
      "Observación MODIFICADA por prueba SGM-23. Hora: " + nuevaHora;
    await observacionInput.sendKeys(nuevaObservacion);
    console.log(` > Nueva Observación: ${nuevaObservacion}`);

    // Campo Hora (Input Time: #horaExamen)
    const horaInput = await waitForElement(driver, By.id("horaExamen"));
    // Limpiar el campo de hora
    await horaInput.sendKeys(
      Key.BACK_SPACE,
      Key.BACK_SPACE,
      Key.BACK_SPACE,
      Key.BACK_SPACE,
      Key.BACK_SPACE,
      Key.BACK_SPACE,
      Key.BACK_SPACE,
      Key.BACK_SPACE,
      Key.BACK_SPACE,
      Key.BACK_SPACE
    );
    // Enviar nueva hora
    await horaInput.sendKeys(nuevaHora);
    console.log(` > Nueva Hora: ${nuevaHora}`);

    // === Paso 5: Guardar el Registro Modificado ===
    console.log(
      '🖱️ Haciendo clic en "Guardar Registro de Examen" para actualizar...'
    );
    // ID del botón de guardar, que maneja tanto incluir como modificar
    const btnGuardar = await waitForElement(
      driver,
      By.id("btnGuardarRegistroExamen")
    );
    await btnGuardar.click();
    await driver.sleep(3000); // === Paso 6: Validar que el Registro fue modificado ===

    console.log("⏳ Esperando la aparición del mensaje de éxito...");
    await waitForElement(driver, By.id("mostrarmodal"), 7000);
    const textoExitoElement = await driver.findElement(
      By.id("contenidodemodal")
    );
    const textoObtenido = await textoExitoElement.getText();
    const textoEsperado = "Registro de examen actualizado exitosamente"; // Mensaje confirmado en modelo PHP

    if (textoObtenido.trim().includes(textoEsperado)) {
      console.log(`✅ Validación exitosa: "${textoEsperado}"`);
      status = "p";
    } else {
      throw new Error(
        `❌ Falló la validación. Esperado: "${textoEsperado}", Obtenido: "${textoObtenido.trim()}"`
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
