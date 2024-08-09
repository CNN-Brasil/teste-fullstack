(() => {
    const collection = document.querySelectorAll('div.pscnn-loterias');

    collection.forEach((element, index) => {
        element.id = `loteria-${index}`;

        new Vue({ el: `#${element.id}` });
    });
})();
