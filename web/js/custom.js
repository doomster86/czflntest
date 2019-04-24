$(document).ready(function () {
    var counter = $('tr:last-child td').html();
    var num = counter;
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
        html = $(".copy-fields-week tr").html();
        $("#rnp_table tr th.week:last-child").after(html);
    });
    $(".add-more-nakaz").click(function () {
        var html = $(".copy-fields-nakaz tr").html();
        $("#rnp_table tr th.nakaz:last").after(html);
        html = $(".copy-fields-teacher tr").html();
        $("#rnp_table tr").each(function () {
            var teacher = $(this).find('td.teacher:last option:selected').val();
            $(this).find('td.teacher:last').after(html);
            $(this).find('td.teacher:last select').val(teacher);
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
        var coolspan = $('#weeks').prop("colSpan");
        $('#rnp_table th').eq( index).remove();
        $("#rnp_table tbody tr, .copy-fields-course").each(function () {
            $(this).find('td').eq(index+coolspan-1).remove();
        });
    });
    $(".deletesubject, .deletemodule, .deletenakaz, .deletetable").click(function() {
        $("input").removeAttr("required");
        $("select").removeAttr("required");
    });
    $("#group").change(function(){
        if ($(this).val().length){
            $(".week-selector").hide()
            $("#week").prop('disabled', 'disabled');
        } else {
            $(".week-selector").show();
            $("#week").prop( "disabled", false );
        }
    });
    if ($("#group").val().length){
        $(".week-selector").hide()
        $("#week").prop('disabled', 'disabled');
    } else {
        $(".week-selector").show();
        $("#week").prop( "disabled", false );
    }

    $("#dolzh").on("click", function () {
        var text = $("#dolzh").html();
        $("#dolzh-append textarea").val(text)
        $("#dolzh-append").show();
            $('#dolzh').hide();
    });
    $("#dolzh-save").on("click", function () {
        var text = $("#dolzh-append textarea").val();
        if (text.length) {
            $("#dolzh").html(text);
            $("#dolzh-append").hide();
            $('#dolzh').show();
        }
    });

    $("#initial").on("click", function () {
        var text = $("#initial").html();
        $("#initial-append textarea").val(text)
        $("#initial-append").show();
        $('#initial').hide();
        //textArea.hide()
    });
    $("#initial-save").on("click", function () {
        var text = $("#initial-append textarea").val();
        if (text.length) {
            $("#initial").html(text);
            $("#initial-append").hide();
            $('#initial').show();
        }
    });

    $("#footer-1").on("click", function () {
        var text = $("#footer-1").html();
        text = text.replace(/&nbsp;/g, ' ').replace(/<br.*?>/g, '\n');
        $("#footer-1-append textarea").val(text)
        $("#footer-1-append").show();
        $('#footer-1').hide();
        //textArea.hide()
    });
    $("#footer-1-save").on("click", function () {
        var text = $("#footer-1-append textarea").val();
        if (text.length) {
            text = text.replace(/\r\n|\r|\n/g,"<br />")
            $("#footer-1").html(text);
            $("#footer-1-append").hide();
            $('#footer-1').show();
        }
    });

    $("#footer-2").on("click", function () {
        var text = $("#footer-2").html();
        text = text.replace(/&nbsp;/g, ' ').replace(/<br.*?>/g, '\n');
        $("#footer-2-append textarea").val(text)
        $("#footer-2-append").show();
        $('#footer-2').hide();
        //textArea.hide()
    });
    $("#footer-2-save").on("click", function () {
        var text = $("#footer-2-append textarea").val();
        if (text.length) {
            text = text.replace(/\r\n|\r|\n/g,"<br />")
            $("#footer-2").html(text);
            $("#footer-2-append").hide();
            $('#footer-2').show();
        }
    });
});
