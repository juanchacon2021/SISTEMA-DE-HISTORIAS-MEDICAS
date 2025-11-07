// === DEPENDENCIAS ===
const { Builder, By, until, Key } = require("selenium-webdriver");
const xmlrpc = require("xmlrpc");

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL =
  "http://localhost/testlink-code-testlink_1_9_20_fixed/lib/api/xmlrpc/v1/xmlrpc.php";
const DEV_KEY = "0808f3b59861f5c2a52a7d5ca1fab8fa"; // tu API Key
const TEST_CASE_EXTERNAL_ID = "SGM-6"; // ✅ ID para Eliminar Jornada
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

// === TEST AUTOMATIZADO: ELIMINAR JORNADA (SGM-5) ===
async function runTest() {
  let driver = await new Builder().forBrowser("MicrosoftEdge").build();
  let status = "f"; // f = failed | p = passed
  let notes = "";

  try {
    // === Paso 1 a 3: Login y Navegar a Jornadas ===
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
    await driver.sleep(2000);

    console.log('🖱️ Haciendo clic en el enlace "Jornadas"...');
    await waitForElement(
      driver,
      By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/jornadas"]')
    );
    await driver
      .findElement(By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/jornadas"]'))
      .click();
    await driver.sleep(2000); // === Paso 4: Esperar la carga de la tabla y hacer clic en Eliminar ===

    console.log("🖱️ Esperando que se carguen las jornadas en la tabla...");
    // Esperar explícitamente una fila de datos para asegurar que la tabla se cargó con AJAX.
    await driver.wait(
      until.elementLocated(
        By.css("#resultadoJornadas tr:not(.dataTables_empty)")
      ),
      10000,
      "Timeout: No se encontraron jornadas para eliminar. Asegúrese de que haya datos en la base de datos."
    );
    console.log(
      '🗑️ Haciendo clic en el botón "Eliminar" de la primera jornada...'
    );
    // *** CORRECCIÓN DEL SELECTOR: Cambiado a 'confirmarEliminar' ***
    const btnEliminar = await waitForElement(
      driver,
      By.css("#resultadoJornadas button[onclick*='confirmarEliminar']")
    ); // *************************************************************
    await btnEliminar.click();
    console.log("   > Modal de Confirmación de Eliminación Abierto.");
    await driver.sleep(1000);

    // === Paso 5: Confirmar la eliminación en el modal ===
    console.log("🖱️ Confirmando la eliminación...");
    // Se mantiene el ID 'btnConfirmarEliminar' ya que aparece en jornadas.php
    const btnConfirmar = await waitForElement(
      driver,
      By.id("btnConfirmarEliminar")
    );
    await btnConfirmar.click();
    await driver.sleep(2000); // Esperar que la acción AJAX se complete // === Paso 6: Validar que la jornada fue eliminada ===

    console.log("⏳ Esperando la aparición del mensaje de éxito...");
    // El mensaje de éxito general aparece en el modal 'mostrarmodal'
    await waitForElement(driver, By.id("mostrarmodal"), 5000);
    const textoExitoElement = await driver.findElement(
      By.id("contenidodemodal")
    );
    const textoObtenido = await textoExitoElement.getText();
    const textoEsperado = "Jornada eliminada exitosamente";

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
