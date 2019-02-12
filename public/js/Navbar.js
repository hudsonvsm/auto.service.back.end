$('#options').flagStrap({
    countries: {
        "BG": "Български",
        "GB": "English"
    },
    placeholder: false,
    buttonSize: "btn-sm",
    buttonType: "btn-info w-10rem",
    labelMargin: "1rem",
    scrollable: false,
    scrollableHeight: "350px",
    onSelect: function (value, element) {
        if (value === 'GB') value = 'en';

        var regex = new RegExp('\\b/' + LANG + '/\\b','g');

        location = location.toLocaleString().replace(regex, '/' + value.toLowerCase() + '/');
    }
});
