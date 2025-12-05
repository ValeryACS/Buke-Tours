import { printTableInPDF } from "./invoices-module.js";

document.addEventListener("DOMContentLoaded", () => {
  const printToPdfButton = document.getElementById("print-invoices-to-pdf");

  printToPdfButton.addEventListener("click", printTableInPDF);
});
