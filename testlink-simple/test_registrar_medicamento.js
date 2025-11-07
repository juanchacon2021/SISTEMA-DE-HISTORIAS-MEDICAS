/*...*/
const { Builder, By, until, Key } = require("selenium-webdriver");
const xmlrpc = require("xmlrpc");
const fs = require('fs');
const path = require('path');

const TESTLINK_URL = "http://localhost/testlink/lib/api/xmlrpc/v1/xmlrpc.php";
const DEV_KEY = "87c0ed89622a1f6767e3e5d414578e97";
const TEST_CASE_EXTERNAL_ID = "SGM-7"; // nuevo ID sugerido
const TEST_PLAN_ID = 3;
const BUILD_NAME = "v.1";

async function waitForElement(driver, by, timeout = 5000) {
  return driver.wait(until.elementLocated(by), timeout);
}

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
      if (error) console.error("⚠️ Error al enviar resultado a TestLink:", error);
      else console.log("📤 Resultado enviado a TestLink:", value);
    });
  } catch (error) {
    console.error("⚠️ No se pudo conectar con TestLink:", error);
  }
}

async function runTest() {
  let driver = await new Builder().forBrowser("MicrosoftEdge").build();
  let status = "f";
  let notes = "";

  try {
    await driver.get("http://localhost/SISTEMA-DE-HISTORIAS-MEDICAS/");
    await driver.sleep(2000);

    // Capturar captcha
    const captchaElement = await driver.findElement(By.id("captcha-code"));
    const captchaValue = await captchaElement.getText();

    await waitForElement(driver, By.id("cedula"));
    await driver.findElement(By.id("cedula")).sendKeys("32014004");
    await driver.findElement(By.id("clave")).sendKeys("Dino1234");
    await driver.findElement(By.id("captcha")).sendKeys(captchaValue);
    await driver.findElement(By.id("entrar")).click();
    await driver.sleep(2000);

    // Navegar a Inventario
    await waitForElement(driver, By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/inventario"]'), 5000);
    await driver.findElement(By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/inventario"]')).click();
    await driver.sleep(2000);

    // Abrir formulario nuevo medicamento (selector asumido)
    console.log('Abriendo modal de registro de medicamento');
    const btnNuevo = await waitForElement(driver, By.id("registrarmedicamento"), 5000);
    await btnNuevo.click();
    // Esperar a que el input nombre sea visible (modal abierto)
    await waitForElement(driver, By.id("nombre"), 5000);
    await driver.wait(async () => {
      try {
        const el = await driver.findElement(By.id('nombre'));
        return await el.isDisplayed();
      } catch (e) { return false; }
    }, 5000);
    await driver.sleep(1000);

    // Llenar formulario (IDs asumidos basados en modelo)
    const nombre = "Medicamento Test " + Math.floor(Math.random() * 10000);
    await waitForElement(driver, By.id("nombre"), 3000);
    await driver.findElement(By.id("nombre")).sendKeys(nombre);
  await driver.findElement(By.id("descripcion")).sendKeys("Descripción prueba automática");
  // Seleccionar una unidad de medida válida (valores disponibles: mg, ml, unidades, frascos, ampollas, comprimidos)
  await waitForElement(driver, By.id("unidad_medida"), 3000);
  const selectUnidad = await driver.findElement(By.id("unidad_medida"));
  await selectUnidad.click();
  // Elegir la opción 'unidades'
  await waitForElement(driver, By.css('#unidad_medida option[value="unidades"]'), 2000);
  await driver.findElement(By.css('#unidad_medida option[value="unidades"]')).click();
  await driver.sleep(200);
  await driver.findElement(By.id("stock_min")).sendKeys("5");

  // Guardar: botón real en la vista es 'procesoMedicamento'
  console.log('Rellenado formulario, pulsando el botón de proceso');
  await waitForElement(driver, By.id("procesoMedicamento"), 5000);
  await driver.findElement(By.id("procesoMedicamento")).click();
  // Dar tiempo a que el servidor procese y muestre el modal de mensaje
  await driver.sleep(1500);

    // Validar modal de éxito: esperar que el contenido del modal sea no vacío
    await waitForElement(driver, By.id("mostrarmodal"), 7000);
    const contenidoLocator = By.id("contenidodemodal");
    // esperar hasta que el texto del modal tenga contenido
    let texto = "";
    try {
      await driver.wait(async () => {
        try {
          const t = await driver.findElement(contenidoLocator).getText();
          texto = t || "";
          return texto.trim().length > 0;
        } catch (e) { return false; }
      }, 7000);
    } catch (waitErr) {
      // timeout: texto sigue vacío
      texto = await (await driver.findElement(contenidoLocator)).getText().catch(() => "");
    }

    console.log('Texto del modal de respuesta (pos espera):', texto);
    if (!texto || texto.trim().length === 0) {
      // Guardar page source para investigar por qué el modal está vacío
      try {
        const srcDir = path.join(__dirname, 'debug');
        if (!fs.existsSync(srcDir)) fs.mkdirSync(srcDir);
        const htmlFile = path.join(srcDir, `page_${Date.now()}.html`);
        const pageHtml = await driver.getPageSource();
        fs.writeFileSync(htmlFile, pageHtml, 'utf8');
        console.log('Se guardó HTML de la página en', htmlFile);
        notes += ` | page_html: ${htmlFile}`;
      } catch (e) {
        console.error('No se pudo guardar HTML de página:', e.message);
      }
      throw new Error('Mensaje inesperado del servidor: "" (modal vacío). HTML y screenshot guardados para depuración)');
    }

    // Aceptar varias variantes del mensaje que el backend puede devolver
    const valido = /registrad|incluid|exitoso|registrado/i.test(texto);
    if (valido) {
      status = "p";
      notes = `Medicamento creado: ${nombre} - mensaje: ${texto.trim()}`;
    } else {
      throw new Error(`Mensaje inesperado del servidor: "${texto.trim()}"`);
    }
  } catch (error) {
    notes = "Error: " + error.message;
    console.error(error);
    // Tomar screenshot para facilitar depuración
    try {
      const shotsDir = path.join(__dirname, 'screenshots');
      if (!fs.existsSync(shotsDir)) fs.mkdirSync(shotsDir);
      const filename = path.join(shotsDir, `error_${Date.now()}.png`);
      const data = await driver.takeScreenshot();
      fs.writeFileSync(filename, data, 'base64');
      console.log('Screenshot guardado en', filename);
      notes += ` | screenshot: ${filename}`;
    } catch (sErr) {
      console.error('No se pudo guardar screenshot:', sErr.message);
    }
  } finally {
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

runTest();