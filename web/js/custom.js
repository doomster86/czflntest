$(document).ready(function () {
    var counter = $('tr:last-child td').html();
    var num = counter;
    var dataindex = 0
    $(".add-more-course").click(function () {
        counter++;
        $('.courses').val(counter);
        $('tr:first-child td.index').html(counter)
        $('.copy-fields-course tr:last-child td.module').find('input').attr('name', 'modules[' + (counter-1) + '][]');
        $('.copy-fields-course tr:last-child td.teacher').find('select').attr('name', 'teacher[' + (counter-1) + '][]');
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
        $("#rnp_table tr th.nakaz:last").after(html);
        var html = $(".copy-fields-teacher tr").html();
        $("#rnp_table tr").each(function () {
            $(this).find('td.teacher:last').after(html);
        });
        $("#rnp_table tbody tr").each(function () {
            $(this).find('td.teacher:last select').attr('name', 'teacher[' + ($(this).find('td:first').html()-1) + '][]');
        });
        $(".copy-fields-course tr").each(function () {
            $(this).find('td.teacher:last').after(html);
        });
    });
    $("body").on("click", ".remove-course", function () {
        $(this).parents("tr").remove();
        counter--;
        var cnt = 0;
        $("tr td.index").each(function () {
            $(this).html(parseInt(num) + cnt + 1);
            $('#rnp_table tr:last-child td.module').find('input').attr('name', 'modules[' + (cnt) + '][]');
            $('#rnp_table tr:last-child td.teacher').find('select').attr('name', 'teacher[' + (cnt) + '][]');
            cnt++;
        });
    });
    $("body").on("click", ".remove-module", function () {
        var coolspan = $('#weeks').prop("colSpan");
        $('#weeks').prop("colSpan", coolspan - 1);
        var index = $(this).closest('th').index();
        $('#rnp_table .week').eq(index).remove();
        $("#rnp_table tbody tr, .copy-fields-course").each(function () {
            $(this).find('.module').eq( index).remove();
            });
    });
    $("body").on("click", ".remove-nakaz", function () {
        var index = $(this).closest('th').index();
        $('#rnp_table th').eq( index).remove();
        $("#rnp_table tbody tr, .copy-fields-course").each(function () {
            $(this).find('td').eq(index+2).remove();
        });
    });
});
