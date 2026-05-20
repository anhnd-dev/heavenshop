window.removeVietnameseAccents = function (str) {
    const accentsMap = {
        a: "àáảãạâầấẩẫậăằắẳẵặ",
        e: "èéẻẽẹêềếểễệ",
        i: "ìíỉĩị",
        o: "òóỏõọôồốổỗộơờớởỡợ",
        u: "ùúủũụưừứửữự",
        y: "ỳýỷỹỵ",
        d: "đ",
        A: "ÀÁẢÃẠÂẦẤẨẪẬĂẰẮẲẴẶ",
        E: "ÈÉẺẼẸÊỀẾỂỄỆ",
        I: "ÌÍỈĨỊ",
        O: "ÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢ",
        U: "ÙÚỦŨỤƯỪỨỬỮỰ",
        Y: "ỲÝỶỸỴ",
        D: "Đ",
    };

    for (const [nonAccent, accents] of Object.entries(accentsMap)) {
        for (const accent of accents) {
            str = str.replace(new RegExp(accent, "g"), nonAccent);
        }
    }

    return str;
};

window.generateSlug = function (text) {
    return removeVietnameseAccents(text)
        .toLowerCase()

        .trim()

        .replace(/\s+/g, "-")

        .replace(/[^a-z0-9-]/g, "")

        .replace(/-+/g, "-");
};

/*
|--------------------------------------------------------------------------
| Generate Full Slug
|--------------------------------------------------------------------------
|
| parentSlug + currentSlug
|
*/

window.generateFullSlug = function (parentSlug = "", currentName = "") {
    const currentSlug = generateSlug(currentName);

    if (!parentSlug) {
        return currentSlug;
    }

    return `${parentSlug}/${currentSlug}`;
};

/*
|--------------------------------------------------------------------------
| Bind Slug Generator
|--------------------------------------------------------------------------
*/

window.bindSlugGenerator = function (inputSelector, outputSelector) {
    $(inputSelector).on("input", function () {
        const inputValue = $(this).val();

        const slugValue = generateSlug(inputValue);

        $(outputSelector).val(slugValue);
    });
};

/*
|--------------------------------------------------------------------------
| Bind Full Slug Generator
|--------------------------------------------------------------------------
*/

window.bindFullSlugGenerator = function ({
    nameSelector,
    parentSelector,
    outputSelector,
}) {
    function updateSlug() {
        const name = $(nameSelector).val().trim();

        /*
        |--------------------------------------------------------------------------
        | Only generate from NAME
        |--------------------------------------------------------------------------
        */

        const currentSlug = generateSlug(name);

        const parentSlug =
            $(parentSelector).find(":selected").data("slug") || "";

        /*
        |--------------------------------------------------------------------------
        | Avoid duplicate slug
        |--------------------------------------------------------------------------
        */

        let fullSlug = currentSlug;

        if (parentSlug) {
            fullSlug = parentSlug + "/" + currentSlug;
        }

        $(outputSelector).val(fullSlug);
    }

    $(nameSelector).on("input", updateSlug);

    $(parentSelector).on("change", updateSlug);
};
