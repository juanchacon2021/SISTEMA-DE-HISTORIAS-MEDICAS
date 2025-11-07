// === DEPENDENCIAS ===
const { Builder, By, until, Key } = require("selenium-webdriver");
const xmlrpc = require("xmlrpc");

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL =
  "http://localhost/testlink-code-testlink_1_9_20_fixed/lib/api/xmlrpc/v1/xmlrpc.php";
const DEV_KEY = "0808f3b59861f5c2a52a7d5ca1fab8fa"; // tu API Key
const TEST_CASE_EXTERNAL_ID = "SGM-14"; // ✅ ID para Modificar y Eliminar Área
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

// === TEST AUTOMATIZADO: MODIFICAR Y ELIMINAR ÁREA (SGM-14) ===
async function runTest() {
  let driver = await new Builder().forBrowser("MicrosoftEdge").build();
  let status = "f"; // f = failed | p = passed
  let notes = "";

  try {
    // === Paso 1 y 2: Login y Navegar a Pasantías y Pestaña Áreas ===
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
    await driver.sleep(2000);

    console.log('🖱️ Haciendo clic en el enlace "Pasantías"...');
    await waitForElement(
      driver,
      By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/pasantias"]')
    );
    await driver
      .findElement(By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/pasantias"]'))
      .click();
    await driver.sleep(2000);

    // Navegar a la pestaña Áreas
    console.log('🖱️ Navegando a la pestaña "Áreas"...');
    // Usamos By.css('a[href="#areas"]') para mayor robustez en la navegación de pestañas
    const tabAreas = await waitForElement(driver, By.id('areas-tab'));
    await tabAreas.click();
    await driver.sleep(1000);

    // Esperar la carga de datos en la tabla de áreas
    await driver.wait(
      until.elementLocated(By.css("#resultadoAreas tr:not(.dataTables_empty)")),
      10000,
      "Timeout: No se encontraron áreas en la tabla. Asegúrese de que haya al menos un área para modificar/eliminar."
    );

    // =========================================================================
    // === PARTE 1: MODIFICACIÓN DEL ÁREA ======================================
    // =========================================================================
    console.log("\n--- INICIANDO MODIFICACIÓN ---");
    console.log(
      '✏️ Haciendo clic en el botón "Modificar" de la primera área...'
    ); // Busca el primer botón que llama a la función 'editarArea'
    const btnModificar = await waitForElement(
      driver,
      By.css("#resultadoAreas button[onclick*='editarArea']")
    );
    await btnModificar.click();
    console.log("   > Modal de Modificación Abierto.");
    await driver.sleep(1000); // === Modificar datos en el formulario ===

    console.log("✏️ Modificando el nombre del área...");

    // Aseguramos que el campo exista antes de usarlo
    await waitForElement(driver, By.id("nombre_area"));

    // 1. Modificar el nombre
    const nombreAreaInput = await driver.findElement(By.id("nombre_area"));
    const randomSuffix = Math.floor(Math.random() * 1000);
    // Limpieza robusta: Ctrl+A (Seleccionar todo) y luego Delete
    await nombreAreaInput.sendKeys(Key.CONTROL, "a");
    await nombreAreaInput.sendKeys(Key.DELETE);
    await nombreAreaInput.sendKeys(`Area Modificada ${randomSuffix}`);
    console.log("   > Nuevo nombre: Area Modificada " + randomSuffix);

    // === Guardar el área ===
    console.log('🖱️ Haciendo clic en "Guardar Área"...');
    const btnGuardar = await waitForElement(driver, By.id("btnGuardarArea"));
    await btnGuardar.click();
    await driver.sleep(2000); // === Validar que el área fue modificada ===

    console.log("⏳ Esperando el mensaje de éxito de modificación...");
    await waitForElement(driver, By.id("mostrarmodal"), 5000);
    const textoExitoModificacion = await driver
      .findElement(By.id("contenidodemodal"))
      .getText();
    const textoEsperadoModificacion = "Área actualizada exitosamente";

    if (textoExitoModificacion.trim().includes(textoEsperadoModificacion)) {
      console.log(
        `✅ Validación Modificación exitosa: "${textoEsperadoModificacion}"`
      );
    } else {
      throw new Error(
        `❌ Falló la validación de Modificación. Esperado: "${textoEsperadoModificacion}", Obtenido: "${textoExitoModificacion.trim()}"`
      );
    }
    // Cerrar el modal de mensaje (si no se cierra automáticamente)
    const btnCerrarModal = await waitForElement(
      driver,
      By.css("#mostrarmodal .btn-close")
    );
    await btnCerrarModal.click();
    await driver.sleep(1000);

    // =========================================================================
    // === PARTE 2: ELIMINACIÓN DEL ÁREA =======================================
    // =========================================================================
    console.log("\n--- INICIANDO ELIMINACIÓN ---");

    // Esperar la recarga de la tabla después de la modificación
    await driver.sleep(1000);

    console.log(
      '🗑️ Haciendo clic en el botón "Eliminar" de la primera área...'
    ); // Busca el primer botón que llama a la función 'confirmarEliminar'
    const btnEliminar = await waitForElement(
      driver,
      By.css("#resultadoAreas button[onclick*='confirmarEliminar']")
    );
    await btnEliminar.click();
    console.log("   > Modal de Confirmación de Eliminación Abierto.");
    await driver.sleep(1000);

    // === Confirmar la eliminación en el modal ===
    console.log("🖱️ Confirmando la eliminación...");
    const btnConfirmar = await waitForElement(
      driver,
      By.id("btnConfirmarEliminar")
    );
    await btnConfirmar.click();
    await driver.sleep(2000); // === Validar que el área fue eliminada ===

    console.log("⏳ Esperando el mensaje de éxito de eliminación...");
    await waitForElement(driver, By.id("mostrarmodal"), 5000);
    const textoExitoEliminacion = await driver
      .findElement(By.id("contenidodemodal"))
      .getText();
    const textoEsperadoEliminacion = "Área eliminada exitosamente";

    if (textoExitoEliminacion.trim().includes(textoEsperadoEliminacion)) {
      console.log(
        `✅ Validación Eliminación exitosa: "${textoEsperadoEliminacion}"`
      );
      status = "p"; // La prueba completa pasó
    } else {
      throw new Error(
        `❌ Falló la validación de Eliminación. Esperado: "${textoEsperadoEliminacion}", Obtenido: "${textoExitoEliminacion.trim()}"`
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
