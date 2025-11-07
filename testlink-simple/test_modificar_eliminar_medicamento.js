/*...*/
const { Builder, By, until, Key } = require("selenium-webdriver");
const xmlrpc = require("xmlrpc");
const fs = require('fs');
const path = require('path');

const TESTLINK_URL = "http://localhost/testlink/lib/api/xmlrpc/v1/xmlrpc.php";
const DEV_KEY = "87c0ed89622a1f6767e3e5d414578e97";
const TEST_CASE_EXTERNAL_ID = "SGM-11"; // nuevo ID sugerido
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

    const captchaElement = await driver.findElement(By.id("captcha-code"));
    const captchaValue = await captchaElement.getText();

    await waitForElement(driver, By.id("cedula"));
    await driver.findElement(By.id("cedula")).sendKeys("32014004");
    await driver.findElement(By.id("clave")).sendKeys("Dino1234");
    await driver.findElement(By.id("captcha")).sendKeys(captchaValue);
    await driver.findElement(By.id("entrar")).click();
    await driver.sleep(2000);

    // Ir a Inventario
    await waitForElement(driver, By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/inventario"]'), 5000);
    await driver.findElement(By.css('a[href="/SISTEMA-DE-HISTORIAS-MEDICAS/inventario"]')).click();
    await driver.sleep(2000);

  // Esperar a que la tabla cargue filas y seleccionar el primer enlace de modificar
  await waitForElement(driver, By.css("#resultadoMedicamentos tr"), 10000);
  await waitForElement(driver, By.css("#resultadoMedicamentos .modificar"), 10000);
  const btnModificar = await driver.findElement(By.css("#resultadoMedicamentos .modificar"));
  await btnModificar.click();
    await driver.sleep(1000);

    // Modificar nombre
    const nuevo = "Medicamento Mod " + Math.floor(Math.random() * 10000);
    const nombreInput = await waitForElement(driver, By.id("nombre"), 3000);
    await nombreInput.sendKeys(Key.CONTROL, "a");
    await nombreInput.sendKeys(Key.DELETE);
    await nombreInput.sendKeys(nuevo);

  // En la vista real el botón para procesar tiene id 'procesoMedicamento'
  await waitForElement(driver, By.id("procesoMedicamento"), 5000);
  await driver.findElement(By.id("procesoMedicamento")).click();
    await driver.sleep(2000);

    // Validar modificación: esperar que el modal tenga contenido no vacío
    await waitForElement(driver, By.id("mostrarmodal"), 7000);
    const contenidoLocatorMod = By.id("contenidodemodal");
    let textoMod = "";
    try {
      await driver.wait(async () => {
        try {
          const t = await driver.findElement(contenidoLocatorMod).getText();
          textoMod = t || "";
          return textoMod.trim().length > 0;
        } catch (e) { return false; }
      }, 7000);
    } catch (e) {
      // timeout, intentar leer lo que haya
      textoMod = await driver.findElement(contenidoLocatorMod).getText().catch(() => "");
    }
    console.log('Texto del modal (modificación):', textoMod);
    if (!textoMod || textoMod.trim().length === 0) {
      // guardar HTML y screenshot para depurar
      try {
        const debugDir = path.join(__dirname, 'debug');
        if (!fs.existsSync(debugDir)) fs.mkdirSync(debugDir);
        const htmlFile = path.join(debugDir, `page_mod_${Date.now()}.html`);
        const pageHtml = await driver.getPageSource();
        fs.writeFileSync(htmlFile, pageHtml, 'utf8');
        const shotsDir = path.join(__dirname, 'screenshots');
        if (!fs.existsSync(shotsDir)) fs.mkdirSync(shotsDir);
        const shotFile = path.join(shotsDir, `error_mod_${Date.now()}.png`);
        const data = await driver.takeScreenshot();
        fs.writeFileSync(shotFile, data, 'base64');
        console.log('Guardado HTML:', htmlFile, 'screenshot:', shotFile);
        notes += ` | page_html: ${htmlFile} | screenshot: ${shotFile}`;
      } catch (ee) { console.error('Error guardando debug artefactos:', ee.message); }
      throw new Error(`Esperado modificación: mensaje no vacío, Obtenido: "${textoMod.trim()}"`);
    }
    // Aceptar variantes del mensaje
    const validoMod = /actualiz|exitoso|actualizado con éxito/i.test(textoMod);
    if (!validoMod) {
      throw new Error(`Esperado modificación: mensaje de éxito, Obtenido: "${textoMod.trim()}"`);
    }

  // Esperar recarga y eliminar primer registro: usar selector .eliminar
  await driver.sleep(1000);
  await waitForElement(driver, By.css("#resultadoMedicamentos .eliminar"), 5000);
  const btnEliminar = await driver.findElement(By.css("#resultadoMedicamentos .eliminar"));
  await btnEliminar.click();
    await driver.sleep(1000);
    const btnConfirm = await waitForElement(driver, By.id("btnConfirmarEliminar"), 3000);
    await btnConfirm.click();
    await driver.sleep(2000);

    // Validar eliminación: esperar que el modal tenga texto y comprobar mensaje
    await waitForElement(driver, By.id("mostrarmodal"), 7000);
    const contenidoLocatorElim = By.id("contenidodemodal");
    let textoElim = "";
    try {
      await driver.wait(async () => {
        try {
          const t = await driver.findElement(contenidoLocatorElim).getText();
          textoElim = t || "";
          return textoElim.trim().length > 0;
        } catch (e) { return false; }
      }, 7000);
    } catch (e) {
      textoElim = await driver.findElement(contenidoLocatorElim).getText().catch(() => "");
    }
    console.log('Texto del modal (eliminación):', textoElim);
    const validoElim = /eliminad|exitoso|eliminado con éxito/i.test(textoElim);
    if (validoElim) {
      status = "p";
      notes = `Modificar y eliminar completado (último nombre: ${nuevo}) - mensaje: ${textoElim.trim()}`;
    } else {
      throw new Error(`Esperado eliminación: mensaje de éxito, Obtenido: "${textoElim.trim()}"`);
    }
  } catch (error) {
    notes = "Error: " + error.message;
    console.error(error);
  } finally {
    await driver.quit();
    await reportResultToTestLink(status, notes);
  }
}

runTest();