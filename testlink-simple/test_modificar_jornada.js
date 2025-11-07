// === DEPENDENCIAS ===
const { Builder, By, until, Key } = require("selenium-webdriver");
const xmlrpc = require("xmlrpc");

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL =
  "http://localhost/testlink-code-testlink_1_9_20_fixed/lib/api/xmlrpc/v1/xmlrpc.php";
const DEV_KEY = "0808f3b59861f5c2a52a7d5ca1fab8fa"; // tu API Key
const TEST_CASE_EXTERNAL_ID = "SGM-5"; // ✅ ID para Modificar Jornada
const TEST_PLAN_ID = 3; // ✅ tu test plan ID real
const BUILD_NAME = "v.1";

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
  // Ahora waitForElement devuelve el elemento, por lo que esta línea es correcta.
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
      `  > Opción seleccionada para #${selectId} con valor: ${optionValue}`
    );
    return optionValue;
  } else {
    throw new Error(
      `No se encontraron opciones válidas para el select #${selectId}.`
    );
  }
}

// === TEST AUTOMATIZADO: MODIFICAR JORNADA (SGM-4) ===
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
    await driver.sleep(2000); // === Paso 4: Esperar la carga de la tabla y hacer clic en Modificar ===

    console.log("🖱️ Esperando que se carguen las jornadas en la tabla...");
    // Esperar explícitamente una fila de datos.
    await driver.wait(
      until.elementLocated(
        By.css("#resultadoJornadas tr:not(.dataTables_empty)")
      ),
      10000,
      "Timeout: No se encontraron jornadas para modificar. Asegúrese de que haya datos en la base de datos."
    );
    console.log(
      '🖱️ Haciendo clic en el botón "Modificar" de la primera jornada...'
    ); // *** ESTA LLAMADA AHORA RECIBIRÁ EL ELEMENTO ***
    const btnModificar = await waitForElement(
      driver,
      By.css("#resultadoJornadas button[onclick*='editarJornada']")
    );
    await btnModificar.click();
    console.log("   > Modal de Modificación Abierto.");
    await driver.sleep(1000); // === Paso 5: Modificar datos en el formulario ===

    // === Paso 5: Modificar datos en el formulario ===
    console.log("✏️ Modificando datos de la jornada...");
    await waitForElement(driver, By.id("fecha_jornada"));

    // 1. Modificar la ubicación
    const ubicacionInput = await driver.findElement(By.id("ubicacion"));
    await ubicacionInput.sendKeys(Key.CONTROL, "a");
    await ubicacionInput.sendKeys(Key.DELETE);
    await ubicacionInput.sendKeys("Clinica Sur Modificada");
    console.log("   > Ubicación modificada."); // 2. Modificar el conteo de pacientes (Debe hacer clear() primero para modificar)

    await driver.findElement(By.id("pacientes_masculinos")).clear();
    await driver.findElement(By.id("pacientes_masculinos")).sendKeys("25"); // Nuevo valor // 3. Forzar la actualización del total de pacientes
    await driver.executeScript("actualizarContadores();");
    console.log("   > Contadores de pacientes actualizados y recalculados."); // === Paso 6: Guardar la jornada (mismo botón, diferente acción) ===
    console.log('🖱️ Haciendo clic en "Guardar" para modificar...');
    await driver.findElement(By.id("btnGuardarJornada")).click();
    await driver.sleep(2000); // === Paso 7: Validar que la jornada fue modificada ===

    console.log("⏳ Esperando la aparición del mensaje de éxito...");
    await waitForElement(driver, By.id("mostrarmodal"), 5000);
    const textoExitoElement = await driver.findElement(
      By.id("contenidodemodal")
    );
    const textoObtenido = await textoExitoElement.getText();
    const textoEsperado = "Jornada actualizada exitosamente";

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
