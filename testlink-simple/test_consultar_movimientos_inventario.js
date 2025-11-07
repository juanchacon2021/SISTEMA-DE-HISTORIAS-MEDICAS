/*...*/
const { Builder, By, until } = require("selenium-webdriver");
const xmlrpc = require("xmlrpc");
const fs = require('fs');
const path = require('path');

const TESTLINK_URL = "http://localhost/testlink/lib/api/xmlrpc/v1/xmlrpc.php";
const DEV_KEY = "87c0ed89622a1f6767e3e5d414578e97";
const TEST_CASE_EXTERNAL_ID = "SGM-27"; // nuevo ID sugerido
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

    // La vista muestra directamente la tabla de movimientos (#tablaMovimientos).
    // Esperar que la tabla y su tbody se hayan renderizado y contengan filas.
    await waitForElement(driver, By.id("tablaMovimientos"), 7000);
    // esperar filas dentro del tbody de resultadoMovimientos
    let filas = [];
    try {
      await driver.wait(async () => {
        filas = await driver.findElements(By.css("#resultadoMovimientos tr"));
        return filas.length > 0;
      }, 10000);
    } catch (e) {
      // timeout: tomar lo que haya
      filas = await driver.findElements(By.css("#resultadoMovimientos tr"));
    }

    console.log('Filas encontradas en movimientos:', filas.length);
    if (filas.length > 0) {
      status = "p";
      notes = `Movimientos listados: ${filas.length}`;
    } else {
      // Guardar HTML y screenshot para depurar
      try {
        const debugDir = path.join(__dirname, 'debug');
        if (!fs.existsSync(debugDir)) fs.mkdirSync(debugDir);
        const htmlFile = path.join(debugDir, `page_mov_${Date.now()}.html`);
        const pageHtml = await driver.getPageSource();
        fs.writeFileSync(htmlFile, pageHtml, 'utf8');
        const shotsDir = path.join(__dirname, 'screenshots');
        if (!fs.existsSync(shotsDir)) fs.mkdirSync(shotsDir);
        const shotFile = path.join(shotsDir, `error_mov_${Date.now()}.png`);
        const data = await driver.takeScreenshot();
        fs.writeFileSync(shotFile, data, 'base64');
        notes += ` | page_html: ${htmlFile} | screenshot: ${shotFile}`;
      } catch (ee) { console.error('Error guardando debug artefactos:', ee.message); }
      throw new Error("La tabla de movimientos está vacía o no cargó correctamente.");
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