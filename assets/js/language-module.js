/**
 * @function - Usada para retornar la ruta del JSON asociado al lenguage seleccionado
 * @returns {string} - El path del JSON al cual hace referencia el lenguage seleccionad
 */
export const getLanguagePath = () => {
  const languageSelected = localStorage.getItem("lang") ?? "es";
  return `/Buke-Tours/assets/data/languages/${languageSelected}.json`;
};

let languageCache = null;
let languageCacheTimestamp = 0;
/*
* 2550 minutos como tiempo para refrescar el cache debido a que estos
* archivos no van a estar cambiando constantemente, no hay necesidad de estar refrescando constantemente
*/
const LANGUAGE_CACHE_TTL = 1000 * 60 * 2550;
/**
 * @function - Usada para retornar el JSON asociado al lenguage seleccionado usando un cache para no sobrecargar la pagina
 * @returns {Prmosie<Object>} - El JSON al cual hace referencia el lenguage seleccionad
 */
export const getLanguageData = async () => {
  const now = Date.now();
  if (languageCache && now - languageCacheTimestamp < LANGUAGE_CACHE_TTL) {
    return languageCache;
  }
  let language = {};
  try {
    const response = await fetch(getLanguagePath());
    if (response.ok) {
      language = await response.json();
    }
  } catch (error) {
    console.error("Error loading language data", error);
  } finally {
    languageCache = language;
    languageCacheTimestamp = Date.now();
    return language;
  }
};
