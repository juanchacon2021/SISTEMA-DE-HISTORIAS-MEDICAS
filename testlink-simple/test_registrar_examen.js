// === DEPENDENCIAS ===
const { Builder, By, until, Key } = require("selenium-webdriver");
const xmlrpc = require("xmlrpc");

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL =
  "http://localhost/testlink-code-testlink_1_9_20_fixed/lib/api/xmlrpc/v1/xmlrpc.php";
const DEV_KEY = "0808f3b59861f5c2a52a7d5ca1fab8fa"; // tu API Key
const TEST_CASE_EXTERNAL_ID = "SGM-21"; // ✅ ID para Inclusión de Registro de Examen
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

const fecha_e = '11-06-2025';
const hora_e = '1200a';

// === TEST AUTOMATIZADO: INCLUIR REGISTRO DE EXAMEN (SGM-21) ===
async function runTest() {
  let driver = await new Builder().forBrowser("MicrosoftEdge").build();
  let status = "f"; // f = failed | p = passed
  let notes = "";

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

    // === Paso 3: Abrir modal de Registro de Examen ===
    console.log('🖱️ Haciendo clic en el botón "Registrar Examen"...');
    // Buscamos el botón principal de registro en la pestaña de Registros de Examen (la activa por defecto)
    const btnRegistrarExamen = await waitForElement(
      driver,
      By.css('button[onclick="mostrarModalRegistroExamen()"]')
    );
    await btnRegistrarExamen.click();
    await driver.sleep(1000);

    // === Paso 4: Llenar el formulario de Registro ===
    console.log("✏️ Llenando el formulario de registro de examen...");

    // Campo Paciente (Select: #cedula_paciente)
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

    // Campo Fecha (Input Date: #fecha_e)
    console.log(` > Usando Fecha: ${fecha_e}`);
    const fechaInput = await waitForElement(driver, By.id("fechaExamen"));
    await fechaInput.sendKeys(fecha_e);

    // Campo Hora (Input Time: #hora_e)
    console.log(` > Usando Hora: ${hora_e}`);
    const horaInput = await waitForElement(driver, By.id("horaExamen"));
    // Selenium requiere usar backspace para limpiar campos de tipo 'time' en algunos navegadores
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
    await horaInput.sendKeys(hora_e);

    // Campo Observación (Textarea: #observacion_examen)
    const observacionInput = await waitForElement(
      driver,
      By.id("observacionExamen")
    );
    await observacionInput.sendKeys(
      "Registro de examen creado automáticamente el " + fecha_e
    );

    // Nota: La subida de archivos (ruta_imagen) no se incluye en este test para mantener la simplicidad, ya que el modelo PHP lo maneja como opcional.

    // === Paso 5: Guardar el Registro ===
    console.log('🖱️ Haciendo clic en "Guardar Registro de Examen"...');
    // ID del botón de guardar, confirmado en examenes.js
    const btnGuardar = await waitForElement(
      driver,
      By.id("btnGuardarRegistroExamen")
    );
    await btnGuardar.click();
    await driver.sleep(3000); // Esperar un poco más por si el procesamiento es complejo // === Paso 6: Validar que el Registro fue creado ===

    console.log("⏳ Esperando la aparición del mensaje de éxito...");
    await waitForElement(driver, By.id("mostrarmodal"), 7000);
    const textoExitoElement = await driver.findElement(
      By.id("contenidodemodal")
    );
    const textoObtenido = await textoExitoElement.getText();
    const textoEsperado = "Registro de examen creado exitosamente"; // Mensaje confirmado en modelo PHP

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
