// === DEPENDENCIAS ===
const { Builder, By, until, Key } = require("selenium-webdriver");
const xmlrpc = require("xmlrpc");

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL =
  "http://localhost/testlink-code-testlink_1_9_20_fixed/lib/api/xmlrpc/v1/xmlrpc.php";
const DEV_KEY = "0808f3b59861f5c2a52a7d5ca1fab8fa"; // tu API Key
const TEST_CASE_EXTERNAL_ID = "SGM-18"; // ✅ ID para Inclusión de Tipo de Examen
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

// === TEST AUTOMATIZADO: INCLUIR TIPO DE EXAMEN (SGM-18) ===
async function runTest() {
  let driver = await new Builder().forBrowser("MicrosoftEdge").build();
  let status = "f"; // f = failed | p = passed
  let notes = "";

  try {
    // === Paso 1: Login y Navegar a Exámenes ===
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
    await driver.sleep(2000); // === Paso 2: Navegar al módulo de Exámenes ===

    console.log('🖱️ Haciendo clic en el enlace "Exámenes Médicos"...'); // Asumiendo un enlace directo en el menú o barra de navegación
    await waitForElement(
      driver,
      By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/examenes"]')
    );
    await driver
      .findElement(By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/examenes"]'))
      .click();
    await driver.sleep(2000);

    // === Paso 3: Navegar a la pestaña Tipos de Examen y Abrir modal ===
    console.log('🖱️ Navegando a la pestaña "Tipos de Examen"...');
    // Se asume el selector más probable para la pestaña de tipos de examen
    const tabTiposExamen = await waitForElement(
      driver,
      By.id('tipos-tab')
    );
    await tabTiposExamen.click();
    await driver.sleep(1000); // Esperar que la pestaña cambie

    console.log('🖱️ Haciendo clic en el botón "Nuevo Tipo de Examen"...');
    // Se asume el selector más probable para el botón de incluir tipo de examen
    const btnNuevoTipo = await waitForElement(
      driver,
      By.css("button[onclick=\"mostrarModalTipoExamen('incluir')\"]")
    );
    await btnNuevoTipo.click();
    await driver.sleep(1000);

    // === Paso 4: Llenar el formulario del Tipo de Examen ===
    console.log("✏️ Llenando el formulario...");

    // Generar un nombre de examen único
    const nombreExamenUnico = "Test Examen " + Math.floor(Math.random() * 1000);
    console.log(` > Tipo de Examen a registrar: ${nombreExamenUnico}`);

    // Campo Nombre (ID: nombre_examen)
    const nombreExamenInput = await waitForElement(
      driver,
      By.id("nombre_examen")
    );
    await nombreExamenInput.sendKeys(nombreExamenUnico);

    // Campo Descripción (ID: descripcion_examen)
    const descripcionExamenInput = await waitForElement(
      driver,
      By.id("descripcion_examen")
    );
    await descripcionExamenInput.sendKeys(
      "Prueba automatizada de inclusión exitosa del tipo de examen."
    );

    // === Paso 5: Guardar el Tipo de Examen ===
    console.log('🖱️ Haciendo clic en "Guardar Tipo de Examen"...');
    // ID del botón de guardar, confirmado en examenes.js
    const btnGuardar = await waitForElement(
      driver,
      By.id("btnGuardarTipoExamen")
    );
    await btnGuardar.click();
    await driver.sleep(2000); // === Paso 6: Validar que el Tipo de Examen fue registrado ===

    console.log("⏳ Esperando la aparición del mensaje de éxito...");
    await waitForElement(driver, By.id("mostrarmodal"), 5000);
    const textoExitoElement = await driver.findElement(
      By.id("contenidodemodal")
    );
    const textoObtenido = await textoExitoElement.getText();
    const textoEsperado = "Tipo de examen registrado exitosamente"; // Mensaje confirmado en modelo PHP

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
