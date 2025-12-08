/**
 * @function 
 * 
 * Usada para imprimir la tabla de las Facturas en PDF
 * @returns {void}
 */
export const printTableInPDF = function () {
  const customersTable = document.getElementById("invoices_table");
  if (!customersTable) return;

  const printWindow = window.open(
    "",
    "print-invoices",
    "width=1200,height=800"
  );
  if (!printWindow) return;

  const htmlCode = `
      <!DOCTYPE html>
      <html lang="es">
        <head>
          <meta charset="UTF-8" />
          <meta name="viewport" content="width=device-width, initial-scale=1.0" />
          <title>Facturacion de Compra en Buke Tours</title>
          <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
            <style type="text/css" media="all">
            .subtitle-invoice{
                color: #ffffff;
                font-size: 19px;
                font-weight: bold;
                width: 100%;  
                margin: 0 auto;
                background: #282d85;
            }
            h4.subtitulo{
                background: #1c6c6e;
                color: #ffd89c;
                margin: 0 auto !important;
            }
            .subtitulo {
              background: antiquewhite;
              margin: 0 auto !important;
              text-align: center;
              width: 100%;
            }
            .dataTables_filter,.dataTables_length, .pagination{
                display: none !important;
            }
            </style>
        </head>
        <body class="p-4">
          <div class="table-responsive">
            ${customersTable.innerHTML}
          </div>
        </body>
      </html>
    `;

  printWindow.document.open();
  printWindow.document.write(htmlCode);
  printWindow.document.close();
  printWindow.focus();

  printWindow.onload = () => {
    printWindow.print();
    printWindow.close();
  };
};
