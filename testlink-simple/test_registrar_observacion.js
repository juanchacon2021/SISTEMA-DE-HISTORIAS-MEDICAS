// === DEPENDENCIAS ===
const { Builder, By, until } = require("selenium-webdriver");
const xmlrpc = require("xmlrpc");

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL =
  "http://localhost/testlink-code-testlink_1_9_20_fixed/lib/api/xmlrpc/v1/xmlrpc.php";
const DEV_KEY = "0808f3b59861f5c2a52a7d5ca1fab8fa"; // tu API Key
const TEST_CASE_EXTERNAL_ID = "SGM-36"; // ✅ ID para Registrar una nueva Observacion
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

// === TEST AUTOMATIZADO: REGISTRAR OBSERVACIÓN (SGM-36) ===
async function runTest() {
  let driver = await new Builder().forBrowser("MicrosoftEdge").build();
  let status = "f"; // f = failed | p = passed
  let notes = "";
  const nombreObservacion = `obsAutomatica ${Date.now()}`.substring(
    0,
    50
  ); // Generar nombre único

  try {
    // === Paso 1 y 2: Login y Navegar a Consultas Médicas ===
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
    await driver.sleep(2000); // Navegar al módulo de Consultas Médicas

    console.log('🖱️ Navegando al módulo "Consultas Médicas"...');
    await waitForElement(
      driver,
      By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/consultasm"]')
    );
    await driver
      .findElement(By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/consultasm"]'))
      .click();
    await driver.sleep(2000);

    // === Paso 3: Abrir modal de Registro de Observación ===
    console.log(
      "🖱️ Buscando y haciendo clic en el botón para registrar una nueva Observación..."
    );

    // **Asumiendo** que existe un botón con este ID o selector para abrir el modal de Observaciones
    const btnabrirObservacion = await waitForElement(
      driver,
      // Buscamos un botón que abra el modal de registro de observaciones
      By.id("btnobservacion"),
      10000
    );
    await btnabrirObservacion.click();
    await driver.sleep(1500);

    const btnRegistrarObservacion = await waitForElement(
      driver,
      // Buscamos un botón que abra el modal de registro de observaciones
      By.id("regobserv"),
      10000
    );
    await btnRegistrarObservacion.click();
    await driver.sleep(1500);

    // === Paso 4: Llenar el formulario de Registro de Observación ===
    console.log("✏️ Llenando el nombre de la nueva observación...");

    // Campo Nombre de la Observación (ID: nom_observaciones, según consultasm.js)
    const nombreInput = await waitForElement(
      driver,
      By.id("nom_observaciones")
    );
    await nombreInput.sendKeys(nombreObservacion);
    console.log(` > Nombre: ${nombreObservacion}`);

    // === Paso 5: Guardar el Registro ===
    console.log('🖱️ Haciendo clic en "Guardar Observación"...');
    // **Asumiendo** que el botón de guardar dentro del modal tiene el ID: btnGuardarObservacion
    const btnGuardar = await waitForElement(
      driver,
      By.id("proceso2")
    );
    await btnGuardar.click();
    await driver.sleep(3000); // === Paso 6: Validar que el Registro fue creado ===

    console.log("⏳ Esperando la aparición del mensaje de éxito...");
    await waitForElement(driver, By.id("mostrarmodal"), 7000);
    const textoExitoElement = await driver.findElement(
      By.id("contenidodemodal")
    );
    const textoObtenido = await textoExitoElement.getText();
    const textoEsperado = "Registro Incluido"; // Mensaje confirmado en modelo consultasm.php (función incluir2)

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
