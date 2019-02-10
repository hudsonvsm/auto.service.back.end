// $(document).ready(function() {
//     $("#form-signin").on('submit', function (event) {
//         event.preventDefault();
//
//         var $this = $(this);
//         var values = objectBuilderFromInputs($this.find(':input'));
//
//         var method = 'POST';
//
//         console.log(values);
//
//         $.ajax({
//             url: CURRENT_URL + '/Login',
//             method: method,
//             dataType: 'json',
//             contentType: 'application/json; charset=utf-8',
//             data: JSON.stringify(values)
//         }).done(function (data, textStatus, jqXHR) {
//             console.log(data);
//             console.log(textStatus);
//             if (textStatus === "success") {
//
//
//
//                 location.reload();
//
//                 return false;
//             }
//
//             alert('fail');
//         })
//         .fail(function (data, textStatus, jqXHR) {
//             console.log('fail big time');
//         })
//         .always(function (data, textStatus, jqXHR) {
//             console.log('always');
//         });
//     });
// });