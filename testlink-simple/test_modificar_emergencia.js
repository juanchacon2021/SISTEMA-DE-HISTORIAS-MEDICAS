// === DEPENDENCIAS ===
const { Builder, By, until } = require('selenium-webdriver');
const xmlrpc = require('xmlrpc');

// === CONFIGURACIÓN TESTLINK ===
const TESTLINK_URL = 'http://localhost/testlink-code-testlink_1_9_20_fixed/testlink-code-testlink_1_9_20_fixed/lib/api/xmlrpc/v1/xmlrpc.php';
const DEV_KEY = '0ef0ac472356c5dacfdb9353b4a097d1';  // tu API Key
const TEST_CASE_EXTERNAL_ID = 'SGM-3'; // cambia al ID real en tu TestLink
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


        // === Paso 4: Ir al modulo de emergencias ===
        console.log('🖱️ Haciendo clic en el enlace "Emergencias"...');
        await driver.findElement(By.css('a[href="?pagina=emergencias"]')).click();
        await driver.sleep(1000);


        // === Paso 5: Verificar redirección al home ===
        console.log('⏳ Esperando redirección a la página de Emergencias...');
        await driver.wait(until.urlIs('http://localhost/SHM-UPTAEB/SISTEMA-DE-HISTORIAS-MEDICAS/?pagina=emergencias'), 1000);

        // === Paso 6: Busca paciente ===
        const xpathAlternativo = "//label[normalize-space(.)='Buscar:']/input";
        await driver.findElement(By.xpath(xpathAlternativo)).sendKeys('2025-11');
        await driver.sleep(500);



        // === Paso 7: Abrir el modal de registro ===
        console.log('🖱️ Haciendo clic en modificar emergencia...');

        // 1. Esperar a que el elemento sea visible usando el selector CSS a.modificar
        await driver.wait(until.elementIsVisible(driver.findElement(By.css('a.modificar'))), 10000);

        // 2. Hacer clic directamente
        await driver.findElement(By.css('a.modificar')).click();
  
         await driver.sleep(1000);


        // === Paso 8: Ingresar datos ===
        console.log('✏️ Ingresando datos');

        // 1. Hora de Ingreso (Formato HH:MM)
        await driver.findElement(By.id('horaingreso')).sendKeys('1130a');

        // 2. Fecha de Ingreso (Formato YYYY-MM-DD)
        await driver.findElement(By.id('fechaingreso')).sendKeys('05112025');

        // 3. Motivo de Ingreso
        await driver.findElement(By.id('motingreso')).sendKeys('Fuerte dolor abdominal y fiebre alta Modificado');

        // 4. Diagnóstico
        await driver.findElement(By.id('diagnostico_e')).sendKeys('Sospecha de apendicitis aguda Modificado');

        // 5. Procedimiento
        await driver.findElement(By.id('procedimiento')).sendKeys('Examenes de sangre y ecografia abdominal Modificado');

        // 6. Tratamientos
        await driver.findElement(By.id('tratamientos')).sendKeys('Hidratacion intravenosa y analgesico Modificado');

        await driver.sleep(500);

        // === Paso 9: precionar el boton de envio ===
        console.log('🖱️ Haciendo clic en el botón "proceso" para guardar/continuar...');
        // Usamos el ID único para localizar y hacer clic en el botón
        await driver.findElement(By.id('proceso')).click();
        await driver.sleep(1000);

        // === Paso 10: Validar el modal de éxito ===
        console.log('⏳ Esperando la aparición del modal de confirmación...');
        await driver.wait(until.elementIsVisible(driver.findElement(By.id('mostrarmodal'))), 500);

        const textoExitoElement = await driver.findElement(By.id('contenidodemodal'));
        const textoObtenido = await textoExitoElement.getText();
        const textoEsperado = 'Registro Modificado';

        if (textoObtenido.trim() === textoEsperado) {
            console.log(`✅ Validación exitosa: El modal muestra el texto esperado: "${textoEsperado}"`);
        } else {
            // Lanzar un error si la validación falla
            throw new Error(`❌ Falló la validación del modal. Esperado: "${textoEsperado}", Obtenido: "${textoObtenido.trim()}"`);
        }

        console.log('✅ Página de emergencias funciona correctamente');
        notes = 'Pagina de emergencias cargada correctamente.';
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
