/**
 * @function - Usada para retornar la ruta del JSON asociado al lenguage seleccionado
 * @returns {string} - El path del JSON al cual hace referencia el lenguage seleccionad
 */
export const getLanguagePath = () => {
  const languageSelected = localStorage.getItem("lang") ?? "es";
  console.log('languageSelected', languageSelected);
  return `/Buke-Tours/assets/data/languages/${languageSelected}.json`;
};
/**
 * @function - Usada para retornar el JSON asociado al lenguage seleccionado
 * @returns {Prmosie<Object>} - El JSON al cual hace referencia el lenguage seleccionad
 */
export const getLanguageData = async () => {
  let language = {};
  try {
    const response = await fetch(getLanguagePath());
    if (response.ok) {
      language = await response.json();
    }
  } catch (error) {
    console.error("Error loading language data", error);
  } finally {
    return language;
  }
};
