// === DEPENDENCIAS ===
const { Builder, By, until, Key } = require("selenium-webdriver");
const xmlrpc = require("xmlrpc");

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL =
  "http://localhost/testlink-code-testlink_1_9_20_fixed/lib/api/xmlrpc/v1/xmlrpc.php";
const DEV_KEY = "0808f3b59861f5c2a52a7d5ca1fab8fa"; // tu API Key
const TEST_CASE_EXTERNAL_ID = "SGM-13"; // ✅ ID para Incluir Área
const TEST_PLAN_ID = 3; // ✅ tu test plan ID real
const BUILD_NAME = "v.1";

// === FUNCIÓN: Esperar a que un elemento esté presente y DEVOLVERLO ===
async function waitForElement(driver, by, timeout = 5000) {
  const element = await driver.wait(until.elementLocated(by), timeout);
  await driver.wait(until.elementIsVisible(element), timeout);
  return element;
}

// === TEST AUTOMATIZADO: INCLUIR ÁREA (SGM-13) ===
async function runTest() {
  let driver = await new Builder().forBrowser("MicrosoftEdge").build();
  let status = "f"; // f = failed | p = passed
  let notes = "";

  try {
    // === Paso 1 y 2: Login y Navegar a Pasantías ===
    console.log("🧭 Navegando al formulario de login...");
    await driver.get("http://localhost/SISTEMA-DE-HISTORIAS-MEDICAS/"); // ... (Código de Login)
    const captchaElement = await driver.findElement(By.id("captcha-code"));
    const captchaValue = await captchaElement.getText();
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

    // === Paso 3: Navegar a la pestaña Áreas y Abrir modal ===
    console.log('🖱️ Navegando a la pestaña "Áreas"...');
    // Asumimos que la pestaña tiene un href="#areas" o un id similar, basado en el layout común de Bootstrap/AdminLTE
    const tabAreas = await waitForElement(driver, By.id('areas-tab'));
    await tabAreas.click();
    await driver.sleep(1000); // Esperar que la pestaña cambie

    console.log('🖱️ Haciendo clic en el botón "Nueva Área"...');
    // Buscamos el botón que abre el modal de registro de áreas (asumiendo función 'mostrarModalArea')
    const btnNuevaArea = await waitForElement(
      driver,
      By.css("button[onclick=\"mostrarModalArea('incluir')\"]")
    );
    await btnNuevaArea.click();
    await driver.sleep(1000);

    // === Paso 4: Llenar el formulario de área ===
    console.log("✏️ Llenando el formulario de área...");

    // Generar un nombre de área único
    const nombreAreaUnico = "Area Test " + Math.floor(Math.random() * 1000);

    // Esperar y obtener NOMBRE del área (ID: nombre_area)
    const nombreAreaInput = await waitForElement(driver, By.id("nombre_area"));
    await nombreAreaInput.sendKeys(nombreAreaUnico);

    console.log("   > Seleccionando Doctor/Especialista...");
    // Nota: Requerirá la función selectFirstOption que se usó en el script SGM-9 para funcionar.
    const doctorSelect = await waitForElement(
      driver,
      By.id("responsable_id")
    );
    await driver
      .findElement(By.css("#responsable_id option:nth-child(2)"))
      .click();
    await driver.sleep(500);

    // === Paso 5: Guardar el área ===
    console.log('🖱️ Haciendo clic en "Guardar Área"...');
    // El ID del botón de guardar área según pasantias.js
    const btnGuardar = await waitForElement(driver, By.id("btnGuardarArea"));
    await btnGuardar.click();
    await driver.sleep(2000); // === Paso 6: Validar que el área fue registrada ===

    console.log("⏳ Esperando la aparición del mensaje de éxito...");
    await waitForElement(driver, By.id("mostrarmodal"), 5000);
    const textoExitoElement = await driver.findElement(
      By.id("contenidodemodal")
    );
    const textoObtenido = await textoExitoElement.getText();
    const textoEsperado = "Área registrada exitosamente";

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
