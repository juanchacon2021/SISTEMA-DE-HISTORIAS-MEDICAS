/*
  Prueba local de consultar movimientos de inventario
  - No reporta a TestLink
  - Guarda artefactos (HTML + screenshot) si no hay filas
  - Si la variable de entorno SKIP_LOGIN=1 se usa, el test navegará directo a la vista de inventario
*/

const { Builder, By, until } = require('selenium-webdriver');
const fs = require('fs');
const path = require('path');

async function waitForElement(driver, by, timeout = 5000) {
  return driver.wait(until.elementLocated(by), timeout);
}

async function runTest() {
  const skipLogin = process.env.SKIP_LOGIN === '1';
  const phpSession = process.env.PHPSESSID;
  const envCedula = process.env.TEST_CEDULA;
  const envClave = process.env.TEST_CLAVE;
  const baseUrl = 'http://localhost/SISTEMA-DE-HISTORIAS-MEDICAS';
  const driver = await new Builder().forBrowser('MicrosoftEdge').build();
  try {
    // si el usuario pasa una cookie de sesión, inyectarla antes de navegar a inventario
    if (phpSession) {
      console.log('PHPSESSID detectado: inyectando cookie de sesión y navegando a inventario');
      // necesita cargar el dominio primero para poder añadir cookies
      await driver.get(baseUrl + '/');
      try {
        await driver.manage().addCookie({ name: 'PHPSESSID', value: phpSession, path: '/', domain: 'localhost' });
      } catch (cookieErr) {
        console.warn('No se pudo añadir la cookie (es posible que el dominio no coincida):', cookieErr.message);
      }
      await driver.get(baseUrl + '/inventario');
    } else if (skipLogin) {
      console.log('SKIP_LOGIN detected: navegando directo a inventario');
      await driver.get(baseUrl + '/inventario');
    } else {
      console.log('Navegando a la página principal y realizando login automático');
      await driver.get(baseUrl + '/');
      await driver.sleep(1500);
      // intentar leer captcha visible si existe
      try {
        const cElem = await driver.findElement(By.id('captcha-code'));
        const cText = await cElem.getText();
        await waitForElement(driver, By.id('cedula'));
        await driver.findElement(By.id('cedula')).sendKeys('32014004');
        await driver.findElement(By.id('clave')).sendKeys('Dino1234');
        await driver.findElement(By.id('captcha')).sendKeys(cText);
        await driver.findElement(By.id('entrar')).click();
        await driver.sleep(1500);
      } catch (e) {
        console.log('No se pudo completar login automático (captcha no encontrado). Si estás logueado, establece SKIP_LOGIN=1 para omitir el login. Error:', e.message);
        // permitir continuar: intentar ir a inventario
        await driver.get(baseUrl + '/inventario');
      }
    }

    // esperar que la vista se cargue
    await driver.sleep(1200);
    // Esperar por más tiempo el elemento tablaMovimientos; si no aparece, guardar artefactos y fallar con mensaje claro
    try {
      await waitForElement(driver, By.id('tablaMovimientos'), 15000);
    } catch (e) {
      console.error('No se encontró #tablaMovimientos dentro del timeout extendido. Guardando artefactos...');
      try {
        const debugDir = path.join(__dirname, 'debug');
        if (!fs.existsSync(debugDir)) fs.mkdirSync(debugDir);
        const htmlFile = path.join(debugDir, `page_no_table_${Date.now()}.html`);
        const pageHtml = await driver.getPageSource();
        fs.writeFileSync(htmlFile, pageHtml, 'utf8');
        const shotsDir = path.join(__dirname, 'screenshots');
        if (!fs.existsSync(shotsDir)) fs.mkdirSync(shotsDir);
        const shotFile = path.join(shotsDir, `no_table_${Date.now()}.png`);
        const data = await driver.takeScreenshot();
        fs.writeFileSync(shotFile, data, 'base64');
        console.log('Artefactos guardados:', htmlFile, shotFile);
        console.log('Puedes inspeccionar el HTML y la captura para ver por qué la tabla no se renderiza o si hay un bloqueo (login/captcha/permisos).');
        process.exitCode = 4;
      } catch (saveErr) {
        console.error('Error guardando artefactos:', saveErr.message);
        process.exitCode = 5;
      }
      // No llamar driver.quit() aquí: el finally hará el quit una sola vez y evita NoSuchSessionError
      return;
    }

    // esperar filas en el tbody #resultadoMovimientos
    let filas = [];
    try {
      await driver.wait(async () => {
        filas = await driver.findElements(By.css('#resultadoMovimientos tr'));
        return filas.length > 0;
      }, 8000);
    } catch (e) {
      filas = await driver.findElements(By.css('#resultadoMovimientos tr'));
    }

    console.log('Filas encontradas en movimientos:', filas.length);
    if (filas.length > 0) {
      console.log('RESULTADO: PASS - Movimientos listados:', filas.length);
      process.exitCode = 0;
    } else {
      console.error('RESULTADO: FAIL - No se encontraron movimientos. Guardando artefactos...');
      // Guardar HTML y screenshot
      try {
        const debugDir = path.join(__dirname, 'debug');
        if (!fs.existsSync(debugDir)) fs.mkdirSync(debugDir);
        const htmlFile = path.join(debugDir, `page_mov_local_${Date.now()}.html`);
        const pageHtml = await driver.getPageSource();
        fs.writeFileSync(htmlFile, pageHtml, 'utf8');
        const shotsDir = path.join(__dirname, 'screenshots');
        if (!fs.existsSync(shotsDir)) fs.mkdirSync(shotsDir);
        const shotFile = path.join(shotsDir, `error_mov_local_${Date.now()}.png`);
        const data = await driver.takeScreenshot();
        fs.writeFileSync(shotFile, data, 'base64');
        console.log('Artefactos guardados:', htmlFile, shotFile);
      } catch (err) {
        console.error('Error guardando artefactos:', err.message);
      }
      process.exitCode = 2;
    }
  } catch (err) {
    console.error('Error ejecutando la prueba:', err);
    process.exitCode = 3;
  } finally {
    await driver.quit();
  }
}

runTest();
