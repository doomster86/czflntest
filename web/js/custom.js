$(document).ready(function () {
    var counter = $('tr:last-child td').html();
    $(".add-more-course").click(function () {
        counter++;
        $('.courses').val(counter);
        $('tr:first-child td.index').html(counter)
        $('.copy-fields-course tr:last-child td.module').find('input').attr('name', 'modules[' + (counter-1) + '][]');
        var html = $(".copy-fields-course").html();
        $("#rnp_table tbody").append(html);
    });
    $(".add-more-module").click(function () {
        var coolspan = $('#weeks').prop("colSpan");
        $('#weeks').prop("colSpan", coolspan + 1);
        $('.modules').val(coolspan + 1);
        var html = $(".copy-fields-module tr").html();
        $("#rnp_table tbody tr").each(function () {
            $(this).find('td.module:last').after(html);
            console.log($(this).find('td:first').html());
            $(this).find('td.module:last input').attr('name', 'modules[' + ($(this).find('td:first').html()-1) + '][]');
        });
        $(".copy-fields-course tr").each(function () {
            $(this).find('td.module:last').after(html);
        });
        var html = $(".copy-fields-week tr").html();
        $("#rnp_table tr th.week:last-child").after(html);
    });
    $(".add-more-nakaz").click(function () {
        var html = $(".copy-fields-nakaz tr").html();
        $("#rnp_table tr th.nakaz:last-child").after(html);
        var html = $(".copy-fields-teacher tr").html();
        $("#rnp_table tr").each(function () {
            $(this).find('td.teacher:last').after(html);
        });
        $(".copy-fields-course tr").each(function () {
            $(this).find('td.teacher:last').after(html);
        });
    });
});
