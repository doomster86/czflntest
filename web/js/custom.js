$(document).ready(function () {
    var counter = $('tr:first-child td').html();
    $(".add-more-course").click(function () {
        counter++;
        $('tr:first-child td.index').html(counter)
        var html = $(".copy-fields-course").html();
        $("#rnp_table tbody").append(html);
    });
    $(".add-more-module").click(function () {
        var coolspan = $('#weeks').prop("colSpan");
        $('#weeks').prop("colSpan", coolspan + 1);
        var html = $(".copy-fields-module tr").html();
        $("#rnp_table tr").each(function () {
            $(this).find('td.module:last').after(html);
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
