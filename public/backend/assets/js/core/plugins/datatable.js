window.buildDataTable = function ({ element, ajax, columns, options = {} }) {
    return $(element).DataTable({
        processing: true,

        serverSide: true,

        responsive: true,

        language: window.dataTableLanguage,

        ajax,
        columns,

        ...options,
    });
};
