// === DEPENDENCIAS ===
const { Builder, By, until } = require("selenium-webdriver");
const xmlrpc = require("xmlrpc");

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL =
  "http://localhost/testlink-code-testlink_1_9_20_fixed/lib/api/xmlrpc/v1/xmlrpc.php";
const DEV_KEY = "0808f3b59861f5c2a52a7d5ca1fab8fa"; // tu API Key
const TEST_CASE_EXTERNAL_ID = "SGM-55"; // ✅ ID para Registro de planificacion exitoso
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

// === TEST AUTOMATIZADO: REGISTRO DE PLANIFICACIÓN (SGM-55) ===
async function runTest() {
  let driver = await new Builder().forBrowser("MicrosoftEdge").build();
  let status = "f"; // f = failed | p = passed
  let notes = "";
  const contenidoAleatorio = `Publicación de prueba automatizada: ${Date.now()}`;

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

    // === Paso 3: Mostrar el formulario de publicación (botón flotante) ===
    console.log(
      '🖱️ Haciendo clic en el botón flotante para "mostrarFormularioPublicacion"...'
    );
    const btnFlotante = await waitForElement(
      driver,
      // Selector basado en el snippet HTML proporcionado: a.btn-flotante con onclick
      By.id('registrar')
    );
    await btnFlotante.click();
    await driver.sleep(1000);

    // === Paso 4: Llenar el formulario de Publicación ===
    console.log("✏️ Llenando el campo de contenido...");

    // Campo Contenido (Asumido como 'contenido_pub' o 'contenido' basado en el PHP model)
    const contenidoInput = await waitForElement(
      driver,
      By.id("contenido"),
      5000 // Aumentar espera por si el formulario tarda en aparecer
    );
    await contenidoInput.sendKeys(contenidoAleatorio);
    console.log(` > Contenido: ${contenidoAleatorio}`);

    // === Paso 5: Guardar la Publicación ===
    console.log('🖱️ Haciendo clic en "Publicar"...');
    // Asumido el ID del botón de guardar basado en patrones del sistema
    const btnGuardar = await waitForElement(
      driver,
      By.id("procesoPublicacion")
    );
    await btnGuardar.click();
    await driver.sleep(3000); // === Paso 6: Validar que el Registro fue creado ===

    console.log("⏳ Esperando la aparición del mensaje de éxito...");
    await waitForElement(driver, By.id("mostrarmodal"), 7000);
    const textoExitoElement = await driver.findElement(
      By.id("contenidodemodal")
    );
    const textoObtenido = await textoExitoElement.getText();
    const textoEsperado = "Publicación registrada exitosamente"; // Mensaje confirmado en modelo PHP

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
