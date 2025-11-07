// === DEPENDENCIAS ===
const { Builder, By, until, Key } = require("selenium-webdriver");
const xmlrpc = require("xmlrpc");

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL =
  "http://localhost/testlink-code-testlink_1_9_20_fixed/lib/api/xmlrpc/v1/xmlrpc.php";
const DEV_KEY = "0808f3b59861f5c2a52a7d5ca1fab8fa"; // tu API Key
const TEST_CASE_EXTERNAL_ID = "SGM-56"; // ✅ ID para Modificación exitosa de Planificación
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

// === TEST AUTOMATIZADO: MODIFICAR PLANIFICACIÓN (SGM-56) ===
async function runTest() {
  let driver = await new Builder().forBrowser("MicrosoftEdge").build();
  let status = "f"; // f = failed | p = passed
  let notes = "";
  const contenidoModificado = `Publicación MODIFICADA automáticamente: ${Date.now()}`;
  const feedContainerId = "#feedContainer"; // Asumiendo que el feed tiene un contenedor

  try {
    // === Paso 1 y 2: Login y Navegar a Planificación ===
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
    await driver.sleep(2000); // Navegar al módulo de Planificación

    console.log('🖱️ Navegando al módulo "Planificación"...');
    await waitForElement(
      driver,
      By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/planificacion"]')
    );
    await driver
      .findElement(
        By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/planificacion"]')
      )
      .click();
    await driver.sleep(2000);

    // === Paso 3: Abrir el formulario de Modificación ===
    console.log(
      '🖱️ Buscando y haciendo clic en el botón "Modificar" de la primera publicación...'
    );

    // Esperar a que aparezca al menos una publicación
    await driver.wait(
      until.elementLocated(By.css(".publicacion")),
      10000,
      "Timeout: No se encontraron publicaciones en el feed. Asegúrese de que haya al menos una para modificar."
    );

    // Buscar el botón de Modificar dentro de la primera publicación.
    // Asumimos que llama a la función JS 'editarPublicacion'
    const btnModificar = await waitForElement(
      driver,
      By.css(".publicacion button[onclick*='editarPublicacion']"),
      5000
    );
    await btnModificar.click();
    await driver.sleep(1500); // Esperar a que el modal/formulario de edición se cargue con los datos

    // === Paso 4: Modificar el campo de contenido ===
    console.log("✏️ Modificando el contenido de la publicación...");

    // Campo Contenido (ID: contenido_pub)
    const contenidoInput = await waitForElement(driver, By.id("contenido"));
    // Limpieza robusta: Ctrl+A (Seleccionar todo) y luego Delete
    await contenidoInput.sendKeys(Key.CONTROL, "a");
    await contenidoInput.sendKeys(Key.DELETE);
    await contenidoInput.sendKeys(contenidoModificado);
    console.log(` > Nuevo Contenido: ${contenidoModificado}`);

    // Nota: La modificación de imagen es opcional y se omite por simplicidad.

    // === Paso 5: Guardar la Publicación Modificada ===
    console.log('🖱️ Haciendo clic en "Publicar" (Botón de guardar)...');
    // ID del botón de guardar, que maneja tanto incluir como modificar
    const btnGuardar = await waitForElement(
      driver,
      By.id("procesoPublicacion")
    );
    await btnGuardar.click();
    await driver.sleep(3000); // === Paso 6: Validar que el Registro fue modificado ===

    console.log("⏳ Esperando la aparición del mensaje de éxito...");
    await waitForElement(driver, By.id("mostrarmodal"), 7000);
    const textoExitoElement = await driver.findElement(
      By.id("contenidodemodal")
    );
    const textoObtenido = await textoExitoElement.getText();
    const textoEsperado = "Publicación modificada exitosamente"; // Mensaje confirmado en modelo PHP

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
