// === DEPENDENCIAS ===
const { Builder, By, until, Key } = require("selenium-webdriver");
const xmlrpc = require("xmlrpc");

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL =
  "http://localhost/testlink-code-testlink_1_9_20_fixed/lib/api/xmlrpc/v1/xmlrpc.php";
const DEV_KEY = "0808f3b59861f5c2a52a7d5ca1fab8fa"; // tu API Key
const TEST_CASE_EXTERNAL_ID = "SGM-20"; // ✅ ID para Modificación y Eliminación de Tipo de Examen
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

// === TEST AUTOMATIZADO: MODIFICAR Y ELIMINAR TIPO DE EXAMEN (SGM-20) ===
async function runTest() {
  let driver = await new Builder().forBrowser("MicrosoftEdge").build();
  let status = "f"; // f = failed | p = passed
  let notes = "";
  const tablaResultadosId = "#resultadoTiposExamen"; // Selector asumido para la tabla de Tipos de Examen

  try {
    // === Paso 1 y 2: Login y Navegar a Exámenes y Pestaña Tipos de Examen ===
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

    // Navegar a la pestaña Tipos de Examen
    console.log('🖱️ Navegando a la pestaña "Tipos de Examen"...');
    const tabTiposExamen = await waitForElement(
      driver,
      By.id('tipos-tab')
    );
    await tabTiposExamen.click();
    await driver.sleep(1000);

    // Esperar la carga de datos en la tabla de tipos de examen
    await driver.wait(
      until.elementLocated(
        By.css(`${tablaResultadosId} tr:not(.dataTables_empty)`)
      ),
      10000,
      "Timeout: No se encontraron tipos de examen en la tabla. Asegúrese de que haya al menos uno para modificar/eliminar."
    );

    // =========================================================================
    // === PARTE 1: MODIFICACIÓN DEL TIPO DE EXAMEN ============================
    // =========================================================================
    console.log("\n--- INICIANDO MODIFICACIÓN ---");
    console.log(
      '✏️ Haciendo clic en el botón "Modificar" del primer registro...'
    ); // Busca el primer botón que llama a la función 'editarTipoExamen'
    const btnModificar = await waitForElement(
      driver,
      By.css(`${tablaResultadosId} button[onclick*='editarTipoExamen']`)
    );
    await btnModificar.click();
    console.log("   > Modal de Modificación Abierto.");
    await driver.sleep(1000); // === Modificar datos en el formulario ===

    console.log("✏️ Modificando el nombre y descripción...");

    // 1. Modificar el nombre
    const nombreExamenInput = await waitForElement(
      driver,
      By.id("nombre_examen")
    );
    const randomSuffix = Math.floor(Math.random() * 1000);
    const nuevoNombre = `Examen Modificado ${randomSuffix}`;

    // Limpieza robusta: Ctrl+A (Seleccionar todo) y luego Delete
    await nombreExamenInput.sendKeys(Key.CONTROL, "a");
    await nombreExamenInput.sendKeys(Key.DELETE);
    await nombreExamenInput.sendKeys(nuevoNombre);
    console.log(`   > Nuevo nombre: ${nuevoNombre}`);

    // 2. Modificar la descripción
    const descripcionExamenInput = await driver.findElement(
      By.id("descripcion_examen")
    );
    await descripcionExamenInput.sendKeys(Key.CONTROL, "a");
    await descripcionExamenInput.sendKeys(Key.DELETE);
    await descripcionExamenInput.sendKeys(
      "Descripción actualizada por prueba SGM-20."
    );

    // === Guardar el Tipo de Examen ===
    console.log('🖱️ Haciendo clic en "Guardar Tipo de Examen"...');
    const btnGuardar = await waitForElement(
      driver,
      By.id("btnGuardarTipoExamen")
    );
    await btnGuardar.click();
    await driver.sleep(2000); // === Validar que el tipo de examen fue modificado ===

    console.log("⏳ Esperando el mensaje de éxito de modificación...");
    await waitForElement(driver, By.id("mostrarmodal"), 5000);
    const textoExitoModificacion = await driver
      .findElement(By.id("contenidodemodal"))
      .getText();
    const textoEsperadoModificacion = "Tipo de examen actualizado exitosamente";

    if (textoExitoModificacion.trim().includes(textoEsperadoModificacion)) {
      console.log(
        `✅ Validación Modificación exitosa: "${textoEsperadoModificacion}"`
      );
    } else {
      throw new Error(
        `❌ Falló la validación de Modificación. Esperado: "${textoEsperadoModificacion}", Obtenido: "${textoExitoModificacion.trim()}"`
      );
    }
    // Cerrar el modal de mensaje (asumiendo que tiene un botón de cerrar en el encabezado)
    
    await driver.sleep(5000);

    // =========================================================================
    // === PARTE 2: ELIMINACIÓN DEL TIPO DE EXAMEN =============================
    // =========================================================================
    console.log("\n--- INICIANDO ELIMINACIÓN ---");

    // Esperar la recarga de la tabla después de la modificación
    await driver.sleep(1000);

    console.log(
      '🗑️ Haciendo clic en el botón "Eliminar" del primer registro (el modificado)...'
    ); // Busca el primer botón que llama a la función 'confirmarEliminar' // Nota: El examen.js usa 'confirmarEliminar' y pasa el modo 'tipo'.
    const btnEliminar = await waitForElement(
      driver,
      By.css(`${tablaResultadosId} button[onclick*='confirmarEliminar']`)
    );
    await btnEliminar.click();
    console.log("   > Modal de Confirmación de Eliminación Abierto.");
    await driver.sleep(1000);

    // === Confirmar la eliminación en el modal ===
    console.log("🖱️ Confirmando la eliminación...");
    // Se usa el mismo ID de botón de confirmación de eliminación general del sistema
    const btnConfirmar = await waitForElement(
      driver,
      By.id("btnConfirmarEliminar")
    );
    await btnConfirmar.click();
    await driver.sleep(2000); // === Validar que el tipo de examen fue eliminado ===

    console.log("⏳ Esperando el mensaje de éxito de eliminación...");
    await waitForElement(driver, By.id("mostrarmodal"), 5000);
    const textoExitoEliminacion = await driver
      .findElement(By.id("contenidodemodal"))
      .getText();
    const textoEsperadoEliminacion = "Tipo de examen eliminado exitosamente";

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
