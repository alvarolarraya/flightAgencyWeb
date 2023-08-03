var specialElementHandlers = {
    // element with id of "bypass" - jQuery style selector
    '.no-export': function (element, renderer) {
        // true = "handled elsewhere, bypass text extraction"
        return true;
    }
};

function exportPDF(id) {
    var doc = new jsPDF('p', 'pt', 'a4');

    var beginningTable = "<table><thead><tr><th>Origen</th><th>Destino</th><th>Fecha</th><th>Cantidad</th><th>Precio total</th></tr></thead><tbody>";
    var endTable = "</tbody></table>";
    var source = document.getElementById(id);
    source = beginningTable+source.innerHTML+endTable;
    source = source.replace(/<td><a.*td>/g,"");
    var margins = {
        top: 10,
        bottom: 10,
        left: 10,
        width: 595
    };

    doc.fromHTML(
        source,
        margins.left,
        margins.top, {
            'width': margins.width,
            'elementHandlers': specialElementHandlers
        },

        function (dispose) {
            doc.save(id);
        }, margins);
}