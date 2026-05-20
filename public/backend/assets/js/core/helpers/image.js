window.renderImage = function (image, path, fallback, width = 60) {
    const src = image ? `${path}/${image}` : fallback;

    return `
        <img
            src="${src}"
            width="${width}"
        >
    `;
};
