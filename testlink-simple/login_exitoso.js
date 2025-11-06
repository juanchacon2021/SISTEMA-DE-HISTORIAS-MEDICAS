// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink-code-testlink_1_9_20_fixed/testlink-code-testlink_1_9_20_fixed/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '0ef0ac472356c5dacfdb9353b4a097d1';  // tu API Key
const TEST_CASE_EXTERNAL_ID = 'SGM-1'; // cambia al ID real en tu TestLink
const TEST_PLAN_ID = 3; // ✅ tu test plan ID real
const BUILD_NAME = 'v.1';

// === TEST AUTOMATIZADO: LOGIN CORRECTO ===
async function runTest() {
  let driver = await new Builder().forBrowser('MicrosoftEdge').build();
  let status = 'f'; // f = failed | p = passed
  let notes = '';

  try {
    // === Paso 1: Entrar al login ===
    console.log('🧭 Navegando al formulario de login...');
    await driver.get('http://localhost/SHM-UPTAEB/SISTEMA-DE-HISTORIAS-MEDICAS/?pagina=login');

    // Esperar un poco para verificar que la página carga
    await driver.sleep(2000);


       // Esperar que cargue el campo de cédula
       await driver.wait(until.elementLocated(By.id('cedula')), 10000);
       console.log('✅ Página de login cargada correctamente.');

        // ------------------------------------------------------------------
        // 💡 NUEVO PASO: Capturar el código dinámico
        console.log('👀 Leyendo el código dinámico generado...');
        
        // 1. Localiza el DIV que contiene el código (ID: 'captcha-code')
        const captchaElement = await driver.findElement(By.id('captcha-code'));
        
        // 2. Extrae el texto del DIV
        const captchaValue = await captchaElement.getText();
        console.log(`➡️ Código capturado: ${captchaValue}`);
        // ------------------------------------------------------------------
   
       // === Paso 2: Ingresar cedula y contraseña ===
       console.log('✏️ Ingresando cédula y contraseña...');
       await driver.findElement(By.id('cedula')).sendKeys('32014004');
       await driver.findElement(By.id('clave')).sendKeys('Dino1234');     
       await driver.findElement(By.id('captcha')).sendKeys(captchaValue);
        
      // === PASO 3 CORREGIDO: Hacer clic en "Ingresar" ===
       console.log('🖱️ Localizando y forzando clic en "Ingresar" con JS...');
      // 1. Localizar el elemento
      const entrarButton = await driver.findElement(By.id('entrar'));

      // 2. Ejecutar un script de JS para hacer clic directamente,
      // lo cual ignora la intercepción visual del elemento.
      await driver.executeScript("arguments[0].click();", entrarButton);

   
       // === Paso 4: Verificar redirección al home ===
       console.log('⏳ Esperando redirección...');
       await driver.wait(until.urlIs('http://localhost/SHM-UPTAEB/SISTEMA-DE-HISTORIAS-MEDICAS/'), 10000);

    console.log('✅ Página de login cargada correctamente');
    notes = 'Pagina de login cargada correctamente.';
    status = 'p';

  } catch (error) {
    console.error('❌ Error durante la prueba:', error.message);
    notes = 'Error: ' + error.message;
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
      testplanid: TEST_PLAN_ID, // ✅ usamos directamente el número 3
      buildname: BUILD_NAME,
      notes: notes,
      status: status,
    };

    client.methodCall('tl.reportTCResult', [params], function (error, value) {
      if (error) {
        console.error('⚠️ Error al enviar resultado a TestLink:', error);
      } else {
        console.log('📤 Resultado enviado a TestLink:', value);
      }
    });
  } catch (error) {
    console.error('⚠️ No se pudo conectar con TestLink:', error);
  }
}

// === Ejecutar test ===
runTest();
