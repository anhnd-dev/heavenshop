window.dataTableLanguage = {
    sSearch: `
        <span class="fs-14">
            ${window.translations.search}
        </span>
    `,

    sProcessing: `
        ${window.translations.loading}
        <i class="fa fa-spinner" style="transition: 2s;"></i>
    `,

    sLengthMenu: `
        <span class="fs-14">
            ${window.translations.show}
        </span>

        <select class="form-control" style="margin: 0 4px;">

            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="40">40</option>
            <option value="50">50</option>
            <option value="-1">Tất cả</option>

        </select>

        <span class="fs-14">
            ${window.translations.record}
        </span>
    `,

    sInfo: `
        ${window.translations.show}
        _START_
        ${window.translations.to}
        _END_
        ${window.translations.of}
        _TOTAL_
        ${window.translations.record}
    `,

    sEmptyTable: window.translations.no_data,

    oPaginate: {
        sNext: '<i class="fa fa-chevron-right"></i>',
        sPrevious: '<i class="fa fa-chevron-left"></i>',
    },
};
