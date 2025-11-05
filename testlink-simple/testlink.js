const { Builder, By, until } = require('selenium-webdriver');
const axios = require('axios');

(async () => {
  // 🔧 Configuración de TestLink
  const testlink = {
    devKey: 'TU_API_KEY',
    testprojectname: 'NombreProyecto',
    testplanname: 'NombrePlan',
    testcaseexternalid: 'TC-1',
    buildname: 'Build1',
    url: 'https://sgm-uptaeb.free.nf/?pagina=login',
  };

  // 🧪 Ejecutar prueba con Selenium
  let driver = await new Builder().forBrowser('chrome').build();
  let resultado = { success: false, notes: '' };

  try {
    await driver.get('https://example.com/login');
    await driver.findElement(By.name('username')).sendKeys('demo');
    await driver.findElement(By.name('password')).sendKeys('demo123');
    await driver.findElement(By.css('button[type="submit"]')).click();
    await driver.wait(until.urlContains('/dashboard'), 5000);

    resultado.success = true;
    resultado.notes = 'Login exitoso';
    console.log('✅ Prueba exitosa');
  } catch (error) {
    resultado.notes = 'Error: ' + error.message;
    console.log('❌ Prueba fallida:', error.message);
  } finally {
    await driver.quit();
  }

  // 📤 Reportar resultado a TestLink
  const payload = {
    method: 'tl.reportTCResult',
    params: {
      devKey: testlink.devKey,
      testprojectname: testlink.testprojectname,
      testplanname: testlink.testplanname,
      testcaseexternalid: testlink.testcaseexternalid,
      buildname: testlink.buildname,
      status: resultado.success ? 'p' : 'f',
      notes: resultado.notes,
    },
    id: 1,
    jsonrpc: '2.0',
  };

  try {
    const res = await axios.post(testlink.url, payload, {
      headers: { 'Content-Type': 'application/json' },
    });
    console.log('📤 Resultado enviado a TestLink');
  } catch (error) {
    console.error('⚠️ Error al reportar a TestLink:', error.message);
  }
})();