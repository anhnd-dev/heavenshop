window.initializeSelect2 = function (selector) {
    $(selector).select2({
        theme: "bootstrap4",

        width: "style",

        placeholder: $(selector).attr("placeholder"),

        allowClear: Boolean($(selector).data("allow-clear")),
    });
};

window.initSelect2List = function (selectors = [], parent = "body") {
    selectors.forEach((selector) => {
        const $el = $(selector);

        if (!$el.length) {
            return;
        }

        $el.select2({
            theme: "bootstrap4",

            width: "100%",

            dropdownParent: $(parent),
        });
    });
};
